<?php 

class BookController extends BaseController
{
    public function showAll($location='all',$language='all',$category='all')
    {
        // -------------- get the locations -----------------
        $locations = Location::havingBooks();
        // set current location
        $currentLocation = false;
        //$currentLocation = $locations->find($location);
        $currentLocation = $locations->filter(function($thisLocation) use ($location)
            {
                if ($thisLocation->Location == $location) {
                    return true;
                }
            });
        if ($currentLocation->count() == 1)
        {
            $currentLocation = $currentLocation->first(); 
            $cLocationID = $currentLocation->ID;
        }
        else
        {
            $currentLocation = $location;
            $cLocationID = 0;
        }

        // --------------- get the languages ---------------
        $languages = Language::orderBy('LanguageEnglish')
                                ->get();
        // set current language
        $currentLanguage = false;
        //$currentLanguage = $languages->find($language);
        $currentLanguage = $languages->filter(function($thisLanguage) use ($language)
            {
                if ($thisLanguage->LanguageEnglish == $language) {
                    return true;
                }
            });
        if ($currentLanguage->count()==1)
        {
            $currentLanguage = $currentLanguage->first();
            $cLanguageID = $currentLanguage->ID;
        }
        else
        {
            $currentLanguage = $language;
            $cLanguageID = 0;
        }

        // ----------------- get the categories ------------------
        $categories = Category::orderBy('Category')
                                ->get();
        // set current category
        $currentCategory = false;
        $currentCategory = $categories->filter(function($thisCategory) use ($category)
            {
                if ($thisCategory->Category == $category) {
                    return true;
                }
            });
        if ($currentCategory->count()==1)
        {
            $currentCategory = $currentCategory->first();
            $cCategoryID = $currentCategory->ID;
        }
        else
        {
            $currentCategory = $category;
            $cCategoryID = 0;
        }

        // ------------- get the books -----------------
        $paginationItemCount = Config::get('view.pagination-itemcount');

        $books = null;
        if (($location == 'all') && ($language == 'all') && ($category == 'all'))
            $books = FlatBook::checked()
                            ->orderBy('Title', 'asc')
                            ->orderBy('Author1', 'asc')
                            ->paginate($paginationItemCount);
        else
            $books = FlatBook::filtered($cLocationID,$cLanguageID,$cCategoryID);
        
        return View::make('booksIndex',
           array('books' => $books, 
               'locations' => $locations, 
               'currentLocation' => $currentLocation,
               'languages' => $languages,
               'currentLanguage' => $currentLanguage,
               'categories' => $categories,
               'currentCategory' => $currentCategory));
    }

    public function search($term = 'abc')
    {
        // $searchTerm = Input::get('searchTerm');
        $result = Librarian::search($term);
        var_dump($result);
    }

    public function myBooks()
    {
        if (!Session::has('loggedInUser'))
                return Redirect::route('login');

        $userID = Session::get('loggedInUser')->UserID;
        // $books = FlatBook::myBooks($userID);
        $books = BookCopy::myBooks($userID);
        //var_dump(count($books));
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
                $bookCategories = $book->Categories()->get();
                $copies = BookCopy::where('BookID', '=', $book->ID)->get();
                return View::make("book",
                    array('book' => $book, 
                        'bookCategories' => $bookCategories,
                        'copies' => $copies));
            }
	    }
    }

    public function addBook()
    {
        $result = FlatBook::addBook(Input::all());

        if ($result[0])
            return $this->addBookThanks($result[1]);
        else
        {
            Session::put('TransactionMessage',['AddBook',$result]);
            return Redirect::to(URL::previous());
        }
    }

    // show thanks screen
    // ask for more info or give option to add more books
    public function addBookThanks($bookID)
    {
        $book = FlatBook::find($bookID);
        $categories = Category::orderBy('Category', 'asc')
                                ->lists('Category','ID');

        return View::make('added-book',
                array('addedBook' => true,
                    'doAddInfo' => true,
                    'categories' => $categories,
                    'book' => $book,
                    'addMoreBooks' => true));
    }

    public function setBookInfo()
    {
        $bookID = Input::get('bookID');
        $book = FlatBook::find($bookID);

        $resultSetExistingCategory = $resultSuggestCategory = null;
        if (is_array(Input::get('CategoryID')))
        {   
            $intCategoryIDs = array_map('intval',Input::get('CategoryID'));
            $resultSetExistingCategory = $book->setCategory($intCategoryIDs);
        }
        
        if (strlen(Input::get('SuggestedCategories'))>0)
            $resultSuggestCategory = $book->suggestCategory(Input::get('SuggestedCategories'));

        return View::make('added-book',
                array('addedInfo' => true,
                    'book' => $book,
                    'addMoreBooks' => true));
    }

    public function serveDeleteBookConfirmation()
    {
        if (!Session::has('loggedInUser'))
            return "";

        $bookCopyID = Input::get('idVal');
        // var_dump($bookCopyID);
        $userID = Session::get('loggedInUser')->UserID;
        $activeTransactions = Transaction::itemCopy($bookCopyID)->count();
        return View::make("templates.deleteConfirmForm",
            array('bookCopyID'=>$bookCopyID,
                'activeTransactions' => $activeTransactions));
    }

    public function deleteBookCopy()
    {
        if (!Session::has('loggedInUser'))
            return "";

        $bookCopyID = Input::get('bookCopyID');
        $bookCopy = BookCopy::find($bookCopyID);
        $bookID = $bookCopy->BookID;
        $result = $bookCopy->delete(true);
        if ($result[0])
        {
            if ($result[1]) // book itself deleted, return to my-books
            {
                Session::put('TransactionMessage',['DeleteBook',[true,'Book copy deleted.']]);   
                return Redirect::to(URL::to('my-books'));
            }                
            else
            {
                Session::put('TransactionMessage',['DeleteBook','Book copy deleted']);
                return Redirect::to(URL::to('book/'.$bookID));
            }
        }
        else
            return $result; // TODO: make this more elegant, rather than just showing $result
    }

    public function editBook()
    {
        // $bookCoverFile = Input::file('book-cover');
        // $result = FileManager::uploadImage($bookCoverFile,'book-covers');
        // var_dump($result);

        if (!Session::has('loggedInUser'))
            return Redirect::route('login');

        $bookID = Input::get('bookID');

        $registerdUser = Session::get('registeredUser');
        // $originalBook = FlatBook::find(Input::get('bookID'));
        $result = $registerdUser->editBookInfo(Input::all());

        // var_dump($result);

        if ($result['success'])
        {
            if ($result['updated'])
                Session::put('TransactionMessage',['EditBook','Book details updated']);
            else
                Session::put('TransactionMessage',['EditBook','Information sent to Librarian for verification, as mulitiple copies of this book exist in MeULib']);
            return Redirect::to(URL::to('book/'.$bookID));
        }
        else
        {
            return Redirect::to(URL::to('book/'.$bookID))->withErrors($result['errors']);
        }
    }
    
}
?>