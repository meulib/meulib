<?php

class FlatBook extends Eloquent {

	protected $table = 'books_flat';
	protected $primaryKey = 'ID';

	public function Copies()
	{
		return $this->hasMany('BookCopy', 'BookID', 'ID');
	}

	public static function addBook($bookDetails)
	{
		if (!Session::has('loggedInUser'))
            return false;

        $userID = Session::get('loggedInUser')->UserID;

		$rules = array(
            'Title' => 'required',
            'Author1' => 'required'
        );
        $validator = Validator::make($bookDetails, $rules);
        if ($validator->fails()) 
        {
            return false;
        }

        $book = new FlatBook;
        $book->Title = $bookDetails['Title'];
        $book->Author1 = $bookDetails['Author1'];
        if (isset($bookDetails['Author2']))
        	$book->Author2 = $bookDetails['Author2'];
        if (isset($bookDetails['Language1']))
	        $book->Language1 = $bookDetails['Language1'];
        if (isset($bookDetails['Language2']))
        $book->Language2 = $bookDetails['Language2'];

        $result = $book->save();
        if ($result)
        	return true;
	}

	public static function myBooks($userID)
	{
		$booksIDs = DB::table('bookcopies')
					->select('BookID')
					->distinct()
					->where('UserID', '=', $userID)
					->lists('BookID');
		if (!empty($booksIDs))
		{
			$books = FlatBook::whereIn('ID',$booksIDs)
						->with('Copies')
						->get();
			return $books;
		}
		else
		{
			return false;
		}
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