<?php
require_once('DB.Connection.php');
require_once("Exceptions.php");
require_once('Biz.Items.php');
require_once('constants.php');

class Transactions
{
	private $dbconn;

	function __construct() 
	{
       $this->dbconn = new DBConn();
	}

	function __destruct()
	{
		$this->dbconn = null;
    }

	public function requestItem($borrowerID, $itemCopyID, $msg)
	{
		$itemCopy = new Items();
		$itemCopyDetails = $itemCopy->getItemCopyDetails($itemCopyID);
		$ownerID = $itemCopyDetails['UserID'];
		$itemID = $itemCopyDetails['BookID'];
		$sql = "insert into ".TBL_PREFIX."transactions_active (Borrower, Lender, "
			. "ItemCopyID, ItemID, Status, CreatedOn, LastChangeOn) values (:borrower, "
			. ":lender, :itemCopyID, :itemID, :status, UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		$params = array('borrower' => $borrowerID, 
			'lender' => $ownerID,
			'itemCopyID' => $itemCopyID,
			'itemID' => $itemID,
			'status' => TRANSACTION_STATUS_REQUESTED);
		try
		{
			$this->dbconn->transactionBegin();
			$transactionID = $this->dbconn->executeInTran($sql,$params, true);
			$sql = "insert into ".TBL_PREFIX."transactions_history (TransactionID, "
				."Status, WhenAction) values (:tranID, :status, UTC_TIMESTAMP())";
			$params = array('tranID' => $transactionID,
				'status' => TRANSACTION_STATUS_REQUESTED);
			$this->dbconn->executeInTran($sql,$params);
			$this->postNewTransactionMessage($transactionID,$borrowerID,$ownerID,$msg);
			$this->dbconn->transactionCommit();
			return true;
		} catch (Exception $e)
		{
			echo "in exception";
			$this->dbconn->transactionRollback();
			echo $e->getMessage();
			return false;
		}
	}

	public function lendItem($userID, $itemCopyID, $lendToID)
	{
		// verify item belongs to user and item is available
		$sql = "select count(*) from ".TBL_PREFIX."bookcopies where ID = :itemCopyID and "
			. "Status = :status and UserID = :userid";
		$params = array('itemCopyID' => $itemCopyID,
			'status' => BOOK_COPY_STATUS_AVAILABLE,
			'userid' => $userID);
		$result = $this->dbconn->selectOneVal($sql,$params,true);
		if (!$result)
		{
			return false;
		}

		// get transaction ID if request transaction exists
		$sql = "select ID from ".TBL_PREFIX."transactions_active where Lender = :lender "
			. "and Borrower = :borrower and ItemCopyID = :itemCopyID and "
			. "status = :status";
		$params = array('lender' => $userID, 
			'borrower' => $lendToID,
			'itemCopyID' => $itemCopyID,
			'status' => TRANSACTION_STATUS_REQUESTED);
		$tranID = $this->dbconn->selectOneVal($sql, $params, true);
		if ($tranID)	// request transaction found
		{
			// lend by updating existing transaction
			$sql = "update ".TBL_PREFIX."transactions_active set Status = :status, "
				. "LastChangeOn = UTC_TIMESTAMP() where ID = :tranID";
			$params = array('status' => TRANSACTION_STATUS_LENT, 
				'tranID' => $tranID);
			try
			{
				$this->dbconn->transactionBegin();
				// update existing transaction
				$this->dbconn->executeInTran($sql,$params);
				// insert new rec in transaction history
				$sql = "insert into ".TBL_PREFIX."transactions_history (TransactionID, "
					."Status, WhenAction) values (:tranID, :status, UTC_TIMESTAMP())";
				$params = array('tranID' => $tranID,
					'status' => TRANSACTION_STATUS_LENT);
				$this->dbconn->executeInTran($sql,$params);
				// update itemCopy status
				$sql = "update ".TBL_PREFIX."bookcopies set Status = :status where "
					. "ID = :itemCopyID";
				$params = array('status' => BOOK_COPY_STATUS_LENTOUT,
					'itemCopyID' => $itemCopyID);
				$this->dbconn->executeInTran($sql,$params);

				// TO DO: send admin message to both users regarding lending

				$this->dbconn->transactionCommit();
			} catch (Exception $e)
			{
				echo "in exception";
				$this->dbconn->transactionRollback();
				echo $e->getMessage();
				return false;
			}
		}
		else
		{
			// TO DO: lend by fresh new transaction	

			return false;
		}
	}

	public function returnItem($userID, $itemCopyID, $returnFromID)
	{
		// verify item belongs to user and item is lent out
		$sql = "select count(*) from ".TBL_PREFIX."bookcopies where ID = :itemCopyID and "
			. "Status = :status and UserID = :userid";
		$params = array('itemCopyID' => $itemCopyID,
			'status' => BOOK_COPY_STATUS_LENTOUT,
			'userid' => $userID);
		$result = $this->dbconn->selectOneVal($sql,$params,true);
		if (!$result)
		{
			return false;
		}

		// get transaction ID of lent transaction
		$sql = "select ID from ".TBL_PREFIX."transactions_active where Lender = :lender "
			. "and Borrower = :borrower and ItemCopyID = :itemCopyID and "
			. "status = :status";
		$params = array('lender' => $userID, 
			'borrower' => $returnFromID,
			'itemCopyID' => $itemCopyID,
			'status' => TRANSACTION_STATUS_LENT);
		$tranID = $this->dbconn->selectOneVal($sql, $params, true);

		if ($tranID)
		{
			// record return

			// mark bookCopy as available
			$sql1 = "update ".TBL_PREFIX."bookcopies set Status = :status where ID = :itemCopyID";
			$params1 = array('status' => BOOK_COPY_STATUS_AVAILABLE,
					'itemCopyID' => $itemCopyID);

			// update/close active transactions
			$sql2 = "update ".TBL_PREFIX."transactions_active set Status = :status, "
				. "LastChangeOn = UTC_TIMESTAMP() where ID = :tranID";
			$params2 = array('status' => TRANSACTION_STATUS_RETURNED, 
				'tranID' => $tranID);

			// enter record in transactions history
			$sql3 = "insert into ".TBL_PREFIX."transactions_history (TransactionID, "
					."Status, WhenAction) values (:tranID, :status, UTC_TIMESTAMP())";

			// TO DO: send admin message to both users regarding item being returned

			try
			{
				$this->dbconn->transactionBegin();
				$this->dbconn->executeInTran($sql1,$params1);
				$this->dbconn->executeInTran($sql2,$params2);
				$this->dbconn->executeInTran($sql3,$params2);
				$this->dbconn->transactionCommit();
			} catch (Exception $e)
			{
				echo "in exception";
				$this->dbconn->transactionRollback();
				echo $e->getMessage();
				return false;
			}
			
		}
		else
		{
			return false;
		}
	}

	public function postTransactionMessage($transactionID, $fromUserID, $toUserID, $msg)
	{
		try
		{
			$this->dbconn->transactionBegin();
			$sql = "insert into ".TBL_PREFIX."transaction_messages (TransactionID) values (:transactionID)";
			$data = array('transactionID'=>$transactionID);
			$msgID = $this->dbconn->executeInTran($sql,$data,true);
			$sql = "insert into ".TBL_PREFIX."messages2 (ID, UserID, FromTo, OtherUserID, "
					. "TransactionID, Message, MsgDateTime, ReadFlag) values (:id, "
					. ":userID, :fromTo, :otherUserID, :transactionID, :message, "
					. "UTC_TIMESTAMP(), :read)";
			$data1 = array('id' => $msgID, 
					'userID' => $fromUserID,
					'fromTo' => MESSAGE_FROM,
					'otherUserID' => $toUserID,
					'transactionID' => $transactionID,
					'message' => $msg,
					'read' => 1);
			$this->dbconn->executeInTran($sql,$data1);
			$data2 = array('id' => $msgID, 
					'userID' => $toUserID,
					'fromTo' => MESSAGE_TO,
					'otherUserID' => $fromUserID,
					'transactionID' => $transactionID,
					'message' => $msg,
					'read' => 0);
			$this->dbconn->executeInTran($sql,$data2);
			$this->dbconn->transactionCommit();
		}
		catch (Exception $e)
		{
			echo "in exception";
			$this->dbconn->transactionRollback();
			echo $e->getMessage();
			return false;
		}
	}

	// same as postTransactionMessage but the transaction is being handled
	// outside this routine
	private function postNewTransactionMessage($transactionID, $fromUserID, $toUserID, $msg)
	{
		$sql = "insert into ".TBL_PREFIX."transaction_messages (TransactionID) values (:transactionID)";
		$data = array('transactionID'=>$transactionID);
		$msgID = $this->dbconn->executeInTran($sql,$data,true);
		$sql = "insert into ".TBL_PREFIX."messages2 (ID, UserID, FromTo, OtherUserID, "
				. "TransactionID, Message, MsgDateTime, ReadFlag) values (:id, "
				. ":userID, :fromTo, :otherUserID, :transactionID, :message, "
				. "UTC_TIMESTAMP(), :read)";
		$data1 = array('id' => $msgID, 
				'userID' => $fromUserID,
				'fromTo' => MESSAGE_FROM,
				'otherUserID' => $toUserID,
				'transactionID' => $transactionID,
				'message' => $msg,
				'read' => 1);
		$this->dbconn->executeInTran($sql,$data1);
		$data2 = array('id' => $msgID, 
				'userID' => $toUserID,
				'fromTo' => MESSAGE_TO,
				'otherUserID' => $fromUserID,
				'transactionID' => $transactionID,
				'message' => $msg,
				'read' => 0);
		$this->dbconn->executeInTran($sql,$data2);
	}

	// gets all messages for a user
	public function getMessages($userID)
	{
		$sql = "SELECT m.ID as ID, TransactionID, FromTo, OtherUserID, FullName as OtherUserName, "
			. "Message FROM ".TBL_PREFIX."messages2 m, "
			. "".TBL_PREFIX."users u WHERE u.UserID = m.OtherUserID and "
			. "m.UserID = :userid order by TransactionID DESC, MsgDateTime ASC";
		//echo $sql;
		$user = array('userid' => $userID);
		$result = $this->dbconn->selectAll($sql, $user, true);
		return $result;
	}

	// gets the list of borrowers who have requested for a particular item
	public function getPendingRequests($itemCopyID)
	{
		$sql = "select * from ".TBL_PREFIX."transactions_active t, ".TBL_PREFIX."users u where "
			. "t.Borrower = u.UserID and t.Status = :tstatus and "
			. "t.ItemCopyID = :itemCopyID order by CreatedOn";
		$params = array('tstatus' => TRANSACTION_STATUS_REQUESTED,
			'itemCopyID' => $itemCopyID );
		$result = $this->dbconn->selectAll($sql, $params, true);
		return $result;
	}

	public function getBorrowerDetails($itemCopyID)
	{
		$sql = "select * from ".TBL_PREFIX."transactions_active t, ".TBL_PREFIX."users u where "
			. "t.Borrower = u.UserID and t.Status = :tstatus and "
			. "t.ItemCopyID = :itemCopyID";
		$params = array('tstatus' => TRANSACTION_STATUS_LENT,
			'itemCopyID' => $itemCopyID );
		$result = $this->dbconn->selectOneRow($sql, $params, true);
		return $result;
	}

	public function getItemsByBorrower($userid)
	{
		$sql = "select * from ".TBL_PREFIX."books_flat bf, ".TBL_PREFIX."transactions_active t where "
			. "bf.ID = t.ItemID and t.Borrower = :userid and t.Status = :status";
		$params = array('userid'=>$userid,
					'status'=>TRANSACTION_STATUS_LENT);
		$result = $this->dbconn->selectAll($sql, $params, true);
		return $result;
	}
}
?>