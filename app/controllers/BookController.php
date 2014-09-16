<?php 

class BookController extends BaseController
{
    public function showAll($category=null)
    {
        if (is_null($category))
        {
        	$books = FlatBook::all();
        }
        if ($category == 'mine')
        {
            if (!Session::has('loggedInUser'))
                return Redirect::to(URL::to('/'));

            $userID = Session::get('loggedInUser')->UserID;
            $books = FlatBook::myBooks($userID);
        }
        if ($category == 'borrowed')
        {
            if (!Session::has('loggedInUser'))
                return Redirect::to(URL::to('/'));

            $borrowerID = Session::get('loggedInUser')->UserID;
            $books = FlatBook::myBorrowedBooks($borrowerID);
        }
        return View::make('booksIndex',array('books' => $books, 'category' => $category));
    }

    public function showSingle($bookId = null)
    {
        //return View::make('single');
        if ($bookId == null)
        {
        	return Redirect::to(URL::previous());
        }
        else
        {
            $book = FlatBook::find($bookId);
            $copies = BookCopy::where('BookID', '=', $book->ID)->get();
            return View::make("book",array('book' => $book, 'copies' => $copies));
	    }
    }

    public function addBook()
    {
        $result = FlatBook::addBook(Input::all());
        if ($result[0])
            Session::put('TransactionMessage',['AddBook',[true,'Book added successfully.']]);
        else
            Session::put('TransactionMessage',['AddBook',$result]);
        return Redirect::to(URL::previous());
    }
}
?>