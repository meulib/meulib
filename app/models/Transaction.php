<?php

class Transaction extends Eloquent {

	protected $table = 'transactions_active';
	protected $primaryKey = 'ID';

	public function LenderUser()
	{
		return $this->hasOne('User', 'UserID', 'Lender');
	}

	public function BorrowerUser()
	{
		return $this->hasOne('User', 'UserID', 'Borrower');
	}

	public function Book()
	{
		return $this->hasOne('FlatBook', 'ID', 'ItemID');
	}	

	public static function request($borrowerID, $itemCopyID, $msg)
	{

		//*** TO DO ***//
		// Consider what to do if a transaction already exists for 
		// this borrower and this itemCopy
		// option 1: let exception occur due to unique key violation
		// option 2: record the new message within existing transaction
						// if transaction status is "Requested"

		$iCopy = BookCopy::findOrFail($itemCopyID);
		$ownerID = $iCopy->UserID;
		$itemID = $iCopy->BookID;
		$tranID = 0;

		DB::beginTransaction();

		try 
		{
			$tran = new Transaction;
			$tran->Borrower = $borrowerID;
			$tran->Lender = $ownerID;
			$tran->ItemCopyID = $itemCopyID;
			$tran->ItemID = $itemID;
			$tran->Status = self::tStatusByKey('T_STATUS_REQUESTED');
			$tran->save();
			$tranID = $tran->ID;

			$tranH = new TransactionHistory;
			$tranH->TransactionID = $tranID;
			$tranH->Status = self::tStatusByKey('T_STATUS_REQUESTED');
			$tranH->save();

			$tranM = new TransactionMessage;
			$tranM->TransactionID = $tranID;
			$tranM->MessageFrom = $borrowerID;
			$tranM->MessageTo = $ownerID;
			$tranM->Message = $msg;
			$tranM->save();
			$msgID = $tranM->ID;

			$userM = new UserMessage;
			$userM->MsgID = $msgID;
			$userM->UserID = $borrowerID;
			$userM->FromTo = TransactionMessage::MsgFromValue();
			$userM->OtherUserID = $ownerID;
			$userM->TransactionID = $tranID;
			$userM->Message = $msg;
			$userM->ReadFlag = 1;
			$userM->save();

			$userM = new UserMessage;
			$userM->MsgID = $msgID;
			$userM->UserID = $ownerID;
			$userM->FromTo = TransactionMessage::MsgToValue();
			$userM->OtherUserID = $borrowerID;
			$userM->TransactionID = $tranID;
			$userM->Message = $msg;
			$userM->save();
		}
		catch (Exception $e)
		{
			DB::rollback();
			throw $e;
		}
		DB::commit();
		return $tranID;
	}

	public static function reply($tranID, $fromUserID, $toUserID, $msg)
	{
		DB::beginTransaction();
		try
		{
			$tranM = new TransactionMessage;
			$tranM->TransactionID = $tranID;
			$tranM->MessageFrom = $fromUserID;
			$tranM->MessageTo = $toUserID;
			$tranM->Message = $msg;
			$tranM->save();

			$msgID = $tranM->ID;

			$userM = new UserMessage;
			$userM->MsgID = $msgID;
			$userM->UserID = $fromUserID;
			$userM->FromTo = TransactionMessage::MsgFromValue();;
			$userM->OtherUserID = $toUserID;
			$userM->TransactionID = $tranID;
			$userM->Message = $msg;
			$userM->ReadFlag = 1;
			$userM->save();

			$userM = new UserMessage;
			$userM->MsgID = $msgID;
			$userM->UserID = $toUserID;
			$userM->FromTo = TransactionMessage::MsgToValue();;
			$userM->OtherUserID = $fromUserID;
			$userM->TransactionID = $tranID;
			$userM->Message = $msg;
			$userM->save();
		}
		catch (Exception $e)
		{
			DB::rollback();
			throw $e;
		}				
		DB::commit();
		return $msgID;
	}

	public static function lend($lenderID, $itemCopyID, $borrowerID)
	{
		$itemCopy = BookCopy::where('ID','=',$itemCopyID)
					->where('UserID','=',$lenderID)
					->where('Status','=', BookCopy::StatusVal('Available'))
					->first();

		if (!$itemCopy)
			throw new TransactionException('Item Not Available');

		$tran = Transaction::where('Borrower','=',$borrowerID)
					->where('itemCopyID','=',$itemCopyID)
					->where('Status','=',self::tStatusByKey('T_STATUS_REQUESTED'))
					->first();

		if (!$tran)
			throw new TransactionException('TransactionID Not Found');

		DB::beginTransaction();
		try 
		{
			$tran->Status = self::tStatusByKey('T_STATUS_LENT');
			$tran->save();

			$tranH = new TransactionHistory;
			$tranH->TransactionID = $tran->ID;
			$tranH->Status = self::tStatusByKey('T_STATUS_LENT');
			$tranH->save();

			$itemCopy->Status = BookCopy::StatusVal('Lent Out');
			$itemCopy->save();
		}
		catch (Exception $e)
		{
			DB::rollback();
			throw $e;
		}				
		DB::commit();
		return $tran->ID;

	}

	public static function returnItem($lenderID, $itemCopyID, $borrowerID)
	{
		$itemCopy = BookCopy::where('ID','=',$itemCopyID)
					->where('UserID','=',$lenderID)
					->where('Status','=', BookCopy::StatusVal('Lent Out'))
					->first();

		if (!$itemCopy)
		{
			//$ql = DB::getQueryLog();
			//$q = serialize($ql);
			throw new TransactionException('Item Not Available');
		}

		$tran = Transaction::where('Borrower','=',$borrowerID)
					->where('itemCopyID','=',$itemCopyID)
					->where('Status','=',self::tStatusByKey('T_STATUS_LENT'))
					->first();

		if (!$tran)
			throw new TransactionException('TransactionID Not Found');

		DB::beginTransaction();
		try 
		{
			$tran->Status = self::tStatusByKey('T_STATUS_RETURNED');
			$tran->save();
			// TO DO: active transaction should be removed actually

			$tranH = new TransactionHistory;
			$tranH->TransactionID = $tran->ID;
			$tranH->Status = self::tStatusByKey('T_STATUS_RETURNED');
			$tranH->save();

			$itemCopy->Status = BookCopy::StatusVal('Available');
			$itemCopy->save();
		}
		catch (Exception $e)
		{
			DB::rollback();
			throw $e;
		}				
		DB::commit();
		return $tran->ID;
	}

	public static function openMsgTransactions($userID)
	{
		// unread messages
		$tranIDs = DB::table('messages2')
					->select('TransactionID')
					->distinct()
					->where('UserID', '=', $userID)
					->where('ReadFlag', '=', 0)
					->lists('TransactionID');

		// transaction details for unread messages
		if (!empty($tranIDs))
		{
			$trans = Transaction::whereIn('ID',$tranIDs)
						->with('LenderUser','BorrowerUser','Book')
						->get();
			return $trans;
		}
		else
			return false;
	}

	public static function tMessages($tranID,$userID)
	{
		$msgs = UserMessage::where('TransactionID', '=', $tranID)
						->where('UserID','=',$userID)
						->with('User','OtherUser')
						->get();
		return $msgs;
	}

	public static function tStatusByVal($sVal)
	{
		switch ($sVal) 
		{
			case 1:
				return 'T_STATUS_REQUESTED';
				break;

			case 2:
				return 'T_STATUS_LENT';
				break;

			case 10:
				return 'T_STATUS_RETURNED';
				break;
			
			default:
				return '';
				break;
		}
	}

	public static function tStatusByKey($sKey)
	{
		switch ($sKey) 
		{
			case 'T_STATUS_REQUESTED':
				return 1;
				break;

			case 'T_STATUS_LENT':
				return 2;
				break;

			case 'T_STATUS_RETURNED':
				return 10;
				break;
			
			default:
				return -1;
				break;
		}
	}

	public static function pendingRequests($itemCopyID, $lenderID)
	{
		$trans = Transaction::where('ItemCopyID','=',$itemCopyID)
					->where('Status','=',self::tStatusByKey('T_STATUS_REQUESTED'))
					->where('Lender','=',$lenderID)
					->with('BorrowerUser')
					->get();
		return $trans;
	}

	public static function borrowerByItemCopy($itemCopyID, $ownerID)
	{
		$tran = Transaction::where('ItemCopyID','=',$itemCopyID)
					->where('Lender','=',$ownerID)
					->where('Status','=',self::tStatusByKey('T_STATUS_LENT'))
					->with('BorrowerUser')
					->first();
		return $tran;
		/*$q = DB::getQueryLog();
		return $q;*/
	}
}

?>