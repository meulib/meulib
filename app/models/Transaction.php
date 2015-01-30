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

	// user sends request for a book to another user
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

		$owner = User::findOrFail($ownerID);
		$borrower = User::findOrFail($borrowerID);
		$item = FlatBook::findOrFail($itemID);

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

			
			$data['email'] = $owner->EMail;
			$data['name'] = $owner->FullName;
			$msgData['to'] = $owner->FullName;
			$msgData['from'] = $borrower->FullName;
			$msgData['bookFullTitle'] = $item->FullTitle();
			$msgData['tranID'] = $tranID;
			$msgData['msg'] = $msg;
			Mail::send('emails.messageRequest',$msgData, function($message) use ($data)
			{
				$message->to($data['email'], $data['name'])
						->subject('A Request For Your Book');
			});
		}
		catch (Exception $e)
		{
			DB::rollback();
			throw $e;
		}
		DB::commit();
		return $tranID;
	}

	// user replies to a message
	public function reply($fromUserID, $toUserID, $msg)
	{
		DB::beginTransaction();
		try
		{
			$tranM = new TransactionMessage;
			$tranM->TransactionID = $this->ID;
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
			$userM->TransactionID = $this->ID;
			$userM->Message = $msg;
			$userM->ReadFlag = 1;
			$userM->save();

			$userM = new UserMessage;
			$userM->MsgID = $msgID;
			$userM->UserID = $toUserID;
			$userM->FromTo = TransactionMessage::MsgToValue();;
			$userM->OtherUserID = $fromUserID;
			$userM->TransactionID = $this->ID;
			$userM->Message = $msg;
			$userM->save();

			$this->load('LenderUser','BorrowerUser','Book');
			if ($fromUserID == $this->Lender)
				$fromUser = $this->LenderUser;
			else
				$fromUser = $this->BorrowerUser;
			if ($toUserID == $this->Lender)
				$toUser = $this->LenderUser;
			else
				$toUser = $this->BorrowerUser;

			$item = $this->Book;
			$data['email'] = $toUser->EMail;
			$data['name'] = $toUser->FullName;
			$msgData['to'] = $toUser->FullName;
			$msgData['from'] = $fromUser->FullName;
			$msgData['bookFullTitle'] = $item->FullTitle();
			$msgData['tranID'] = $this->ID;
			$msgData['msg'] = $msg;
			Mail::send('emails.messagePosted',$msgData, function($message) use ($data)
			{
				$message->to($data['email'], $data['name'])
						->subject('A Message For You');
			});
		}
		catch (Exception $e)
		{
			DB::rollback();
			throw $e;
		}				
		DB::commit();
		return $msgID;
	}

	// user lends a book to another user - via Pending Requests
	// SUCCESS RETURN:: TransactionID
	// FAILURE:: Exception
	// TODO:: Make "lend" and "lendDirect" behave same in return behavior
	// on success and failure
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

	// user lends to a person directly
	// may or may not be a library member
	// SUCCESS RETURN: TransactionID in one member array
	// FAILURE: Array, first element false, other elements reason
	// TODO:: Make "lend" and "lendDirect" behave same in return behavior
	// on success and failure
	public static function lendDirect($lenderID, $itemCopyID, $borrowerName, $borrowerEmail = null, $borrowerPhone = null)
	{

		// is item available to be lent?
		$itemCopy = BookCopy::where('ID','=',$itemCopyID)
					->where('UserID','=',$lenderID)
					->where('Status','=', BookCopy::StatusVal('Available'))
					->first();
		if (!$itemCopy)
			return [false,'Item Not Available'];
		$itemID = $itemCopy->BookID;

		$phantomUserResult = UserAccess::addNewPhantomUser($borrowerName,$borrowerEmail,$borrowerPhone);
		if (!$phantomUserResult['UserID'])	// 1st element of result = false => failure
			return [false,$phantomUserResult['msg']];	// user did not get created. Some error in borrower details
		
		// enter lend direct transaction
		$borrowerID = $phantomUserResult['UserID'];

		DB::beginTransaction();

		try 
		{
			$tran = new Transaction;
			$tran->Borrower = $borrowerID;
			$tran->Lender = $lenderID;
			$tran->ItemCopyID = $itemCopyID;
			$tran->ItemID = $itemID;
			$tran->Status = self::tStatusByKey('T_STATUS_LENT');
			$tran->save();
			$tranID = $tran->ID;

			$tranH = new TransactionHistory;
			$tranH->TransactionID = $tranID;
			$tranH->Status = self::tStatusByKey('T_STATUS_LENT');
			$tranH->save();

			$itemCopy->Status = BookCopy::StatusVal('Lent Out');
			$itemCopy->save();
		}
		catch (Exception $e)
		{
			DB::rollback();
			return [false,'DB Error '.$e->getMessage()];
		}
		DB::commit();

		// SEND EMAIL TO BORROWER IF EMAIL GIVEN
		if (strlen($borrowerEmail)>0)
		{
			$owner = User::find($lenderID);

			$msgData['email'] = $borrowerEmail;
			$msgData['borrower'] = $borrowerName;
			$msgData['bookName'] = $itemCopy->Book->FullTitle();
			$msgData['owner'] = $owner->FullName;
			if ($phantomUserResult['isPhantom'])
				$template = 'emails.directLendPhantomUser'; 
			else
				$template = 'emails.directLendRealUser'; 

			Mail::send($template,$msgData, function($message) use ($msgData)
			{
				$message->to($msgData['email'], $msgData['borrower'])
						->subject($msgData['owner'].' lent you '.$msgData['bookName']);
			});	
			
		}
		
		return [$tranID];
	}

	// user records the return of an item
	// public static function returnItem($lenderID, $itemCopyID, $borrowerID)
	public function returnItem()
	{
		$itemCopy = BookCopy::where('ID','=',$this->ItemCopyID)
					->where('UserID','=',$this->Lender)
					->where('Status','=', BookCopy::StatusVal('Lent Out'))
					->first();		

		if (!$itemCopy)
		{
			throw new TransactionException('Item Not Found');
		}

		$tranID = $this->ID;

		DB::beginTransaction();
		try 
		{
			// post to transaction archive
			$tranArchive = new TransactionArchived;
			$tranArchive->ID = $this->ID;
  			$tranArchive->Borrower = $this->Borrower;
  			$tranArchive->Lender = $this->Lender;
  			$tranArchive->ItemCopyID = $this->ItemCopyID;
  			$tranArchive->ItemID = $this->ItemID;
  			$tranArchive->Status = self::tStatusByKey('T_STATUS_RETURNED');
  			$tranArchive->save();

			// post to history
			$tranH = new TransactionHistory;
			$tranH->TransactionID = $this->ID;
			$tranH->Status = self::tStatusByKey('T_STATUS_RETURNED');
			$tranH->save();

			// mark item as available
			$itemCopy->Status = BookCopy::StatusVal('Available');
			$itemCopy->save();

			// delete the transaction from active table
			// $tran->Status = self::tStatusByKey('T_STATUS_RETURNED');
			$this->delete();
		}
		catch (Exception $e)
		{
			DB::rollback();
			throw $e;
		}				
		DB::commit();
		return $tranID;
	}

	// for some reason just cut a transaction short
	// just delete it - record it in archive and history
	// and just delete
	// MAKES NO CHANGE TO ITEMCOPY STATUS
	// $independent: if this is being called from another 
	// already active db transaction then call this with 
	// independent = false
	// then DB begintransaction, commit will not be called here
	// and operations performed here will be part of larger transaction
	public function abort($independent = true)
	{
		$tranID = $this->ID;
		if ($independent)
			DB::beginTransaction();
		try 
		{
			// post to transaction archive
			$tranArchive = new TransactionArchived;
			$tranArchive->ID = $this->ID;
  			$tranArchive->Borrower = $this->Borrower;
  			$tranArchive->Lender = $this->Lender;
  			$tranArchive->ItemCopyID = $this->ItemCopyID;
  			$tranArchive->ItemID = $this->ItemID;
  			$tranArchive->Status = self::tStatusByKey('T_STATUS_ABORTED');
  			$tranArchive->save();

			// post to history
			$tranH = new TransactionHistory;
			$tranH->TransactionID = $this->ID;
			$tranH->Status = self::tStatusByKey('T_STATUS_ABORTED');
			$tranH->save();

			// delete the transaction from active table
			// $tran->Status = self::tStatusByKey('T_STATUS_RETURNED');
			$this->delete();
		}
		catch (Exception $e)
		{
			if ($independent)
				DB::rollback();
			throw $e;
		}
		if ($independent)				
			DB::commit();

		return $tranID;
	}

	// Retrieve Function - No Action
	// all transactions which contain some unread messages returned
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

	// Retrieve Function - No Action
	// messages for a particular transaction returned
	// from the perspective of one of the users
	// of the transaction
	public static function tMessages($tranID,$userID)
	{
		$msgs = UserMessage::where('TransactionID', '=', $tranID)
						->where('UserID','=',$userID)
						->with('User','OtherUser')
						->get();
		return $msgs;
	}

	// Retrieve Function - No Action
	// returns human readable Transaction Status
	// for numeric status saved in db
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

			case -10:
				return 'T_STATUS_ABORTED';
				break;
			
			default:
				return '';
				break;
		}
	}

	// Retrieve Function - No Action
	// returns numeric Transaction Status
	// for human readable keys
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

			case 'T_STATUS_ABORTED':
				return -10;
				break;
			
			default:
				return -1;
				break;
		}
	}

	// Retrieve Function - No Action
	// all pending requests for a particular item returned
	public static function pendingRequests($itemCopyID, $lenderID)
	{
		$trans = Transaction::where('ItemCopyID','=',$itemCopyID)
					->where('Status','=',self::tStatusByKey('T_STATUS_REQUESTED'))
					->where('Lender','=',$lenderID)
					->with('BorrowerUser')
					->get();
		return $trans;
	}

	// Retrieve Function - No Action
	// details of the current borrow transaction for a particular item
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

	// Retrieve Function - No Action
	// Transactions for an ItemCopy
	public function scopeItemCopy($query,$ItemCopyID)
	{
		return $query->where(function ($query) use($ItemCopyID)
						{
						$query->where('ItemCopyID','=', $ItemCopyID);
						});
	}
}

?>