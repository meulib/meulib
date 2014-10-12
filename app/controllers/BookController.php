<?php 

class BookController extends BaseController
{
    public function showAll($location='all')
    {
        $books = null;
        if ($location == 'all')
            $books = FlatBook::orderBy('Title', 'asc')
                            ->orderBy('Author1', 'asc')
                            ->get();
        else
            $books = FlatBook::byLocation($location);

        $locations = Location::havingBooks();
        $currentLocation = false;
        $currentLocation = $locations->find($location);
        if (get_class($currentLocation) == 'Location')
            $currentLocation = $currentLocation->Location . ", " . $currentLocation->Country;
        else
            $currentLocation = $location;
                
        return View::make('booksIndex',
            array('books' => $books, 
                'locations' => $locations, 
                'currentLocation' => $currentLocation));
    }

    public function myBooks()
    {
        if (!Session::has('loggedInUser'))
                return Redirect::to(URL::to('/'));

        $userID = Session::get('loggedInUser')->UserID;
        $books = FlatBook::myBooks($userID);

        return View::make('myBooks',array('books' => $books));
    }

    public function borrowedBooks()
    {
        if (!Session::has('loggedInUser'))
                return Redirect::to(URL::to('/'));

        $borrowerID = Session::get('loggedInUser')->UserID;
        $books = FlatBook::myBorrowedBooks($borrowerID);

        return View::make('borrowedBooks',array('books' => $books));
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
            if ($book == NULL)
                App::abort(404);
            else
            {
                $copies = BookCopy::where('BookID', '=', $book->ID)->get();
                return View::make("book",array('book' => $book, 'copies' => $copies));
            }
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