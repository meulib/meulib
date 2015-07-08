<?php

class BookCopy extends Eloquent {

	use SoftDeletingTrait;

	protected $table = 'bookcopies';
	protected $primaryKey = 'ID';

	public function Owner()
	{
		return $this->hasOne('User', 'UserID', 'UserID');
	}

	public function Book()
	{
		return $this->hasOne('FlatBook', 'ID', 'BookID');
	}

	public function StatusTxt()
	{
		switch ($this->Status) 
		{
			case 1:
				return 'Available';
				break;

			case 2:
				return 'Lent Out';
				break;
		
			default:
				return '';
				break;
		}
	}

	public function niceLentOutDt()
	{
		return TimeKeeper::niceDateNoYear($this->LentOutDt);
	}

	public function daysAgoLentOut()
	{
		$now = time();
	    $datediff = $now - strtotime($this->LentOutDt);
     	return floor($datediff/(60*60*24));
	}

	public static function StatusVal($val)
	{
		switch ($val) 
		{
			case 'Available':
				return 1;
				break;

			case 'Lent Out':
				return 2;
				break;
		
			default:
				return -1;
				break;
		}
	}

	public function scopeAllCopies($query,$BookID)
	{
		return $query->where(function ($query) use($BookID)
				{
					$query->where('BookID','=', $BookID);
				});
	}

	public static function myBorrowedBooks($borrowerID)
	{
		$booksIDs = DB::table('transactions_active')
						->select('ItemCopyID')
						->distinct()
						->where('Borrower','=',$borrowerID)
						->where('Status','=',Transaction::tStatusByKey('T_STATUS_LENT'))
						->lists('ItemCopyID');
		if (!empty($booksIDs))
		{
			$bookCopies = BookCopy::whereIn('ID',$booksIDs)
						->with('Book')
						->orderBy('LentOutDt', 'desc')
						->get();
			return $bookCopies;
		}
		else
			return false;
	}

	// force delete = true aborts transactions if any exist
	// force delete = false prevents delete if transactions exist
	// if no active transactions exist, force delete false and true
	// are same
	// SUCCESS RETURN: Array[trueIfSuccessfullyDeleted,trueIfBookItselfDelete]
	public function delete($forceDelete = false)
	{
		
		if (!Session::has('loggedInUser'))
            return array(false,'No user logged in.');

        $user = Session::get('loggedInUser');
        if ($user->UserID != $this->UserID)
        	return array(false,'User not authorized to delete.');

        $BookID = $this->BookID;
		$BookCopyID = $this->ID;
		$bookItself = FlatBook::find($BookID);
		$emailBody = "Book Copy Deleted: " . $BookCopyID . " : " .
			$bookItself->FullTitle() . " : " . $bookItself->Author1 . " : " .
			$user->UserID . " : " . $user->FullName;

		// figure out of transactions are to be aborted
		$abortTransactions = false;
		$activeTransactions = Transaction::itemCopy($this->ID)->get();
		if (count($activeTransactions) > 0)
		{
			// transactions exist but attempt to delete without
			// force = true
			if (!$forceDelete)
			{
				return array(false,'Active Transactions exist');
			}
			else 
			{
				$abortTransactions = true;	
			}				
		}

		// figure out if the basic book itself is to be deleted
		$deleteBookItself = false;
		$bookCopiesCount = self::allCopies($this->BookID)->count();
		if ($bookCopiesCount == 1)
			$deleteBookItself = true;

		// now do the needful
		DB::beginTransaction();
		try 
		{
			if ($abortTransactions)
			{
				foreach ($activeTransactions as $transaction) 
				{
					// false flag means abort is not independent
					// abort is part of this larger ongoing db transaction
					$transaction->abort(false);
				}
			}

			// delete book copy
			parent::delete();

			// delete book itself and book category
			$bookItself = FlatBook::find($BookID);
			if ($deleteBookItself)
			{
				// delete book itself, call with independent = false
				$bookItself->delete(false); 
			}
			else
			{
				$bookItself->clearCachedCopies();
			}
		}
		catch (Exception $e)
		{
			DB::rollback();
			throw $e;
		}
		DB::commit();

		// email admin notification
		if ($deleteBookItself)
			$emailBody .= " : Base book itself deleted.";
		if ($abortTransactions)
			$emailBody .= " : Transactions aborted.";

		Postman::mailToAdmin("Book Copy Deleted",$emailBody);
		
		return [true,$deleteBookItself];
	}

	public function editSettings($settings)
	{
		if (!Session::has('loggedInUser'))
            return array('success'=>false,'error'=>'No user logged in.');

        $user = Session::get('loggedInUser');
        if ($user->UserID != $this->UserID)
        	return array('success'=>false,'error'=>'Unauthorized');

		try
		{
			$this->ForGiveAway = $settings['ForGiveAway'];
			$this->save();	
		}
		catch (Exception $e)
		{
			return array('success'=>false,'error'=>$e->getMessage());
		}
		return array('success' => true, 'Book Copy setting changed.');
	}

	// possibly defunct
	/*public static function myBooks($UserID)
	{
		$paginationItemCount = Config::get('view.pagination-itemcount');

		return BookCopy::with('Book')
			->where('UserID', $UserID)
			->join('books_flat', 'bookcopies.BookID', '=', 'books_flat.ID')
			->orderBy('books_flat.Title', 'ASC')
			->select('books_flat.ID as BookID',
				'bookcopies.ID as BookCopyID','bookcopies.ForGiveAway',
				'Title','SubTitle','Author1','Author2','Status','LentOutDt')
			->paginate($paginationItemCount);
	}*/
}

?>