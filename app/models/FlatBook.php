<?php

class FlatBook extends Eloquent {

	protected $table = 'books_flat';
	protected $primaryKey = 'ID';

	public function Copies()
	{
		return $this->hasMany('BookCopy', 'BookID', 'ID');
	}

	public static function myBooks($userID)
	{
		$booksIDs = DB::table('bookcopies')
					->select('BookID')
					->distinct()
					->where('UserID', '=', $userID)
					->lists('BookID');
		$books = FlatBook::whereIn('ID',$booksIDs)
					->with('Copies')
					->get();
		return $books;
	}

	public static function myBorrowedBooks($borrowerID)
	{
		$booksIDs = DB::table('transactions_active')
						->select('ItemID')
						->distinct()
						->where('Borrower','=',$borrowerID)
						->where('Status','=',Transaction::tStatusByKey('T_STATUS_LENT'))
						->lists('ItemID');
		$books = FlatBook::whereIn('ID',$booksIDs)
					->with('Copies')
					->get();
		return $books;
	}

}

?>