<?php 

class BookController extends BaseController
{
    public function showAll($mode = 'all', $location='all',$language='all',$category='all')
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
        $languages = Language::getAllLanguages();
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
        $categories = Category::getAllCategories();
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
        if (($mode == 'all') && ($location == 'all') && ($language == 'all') && ($category == 'all'))
            $books = FlatBook::getAllBooks();
            // $books = FlatBook::checked()
            //                 ->orderBy('updated_at','desc')
            //                 ->orderBy('Title', 'asc')
            //                 ->paginate($paginationItemCount);
        else
            $books = FlatBook::filtered($mode,$cLocationID,$cLanguageID,$cCategoryID);
        
        return View::make('booksIndex',
           array('books' => $books, 
                'currentMode' => $mode,
               'locations' => $locations, 
               'currentLocation' => $currentLocation,
               'languages' => $languages,
               'currentLanguage' => $currentLanguage,
               'categories' => $categories,
               'currentCategory' => $currentCategory))->render();
    }

    public function borrowedBooks()
    {
        if (!Session::has('loggedInUser'))
                return Redirect::to(URL::to('/'));

        $borrowerID = Session::get('loggedInUser')->UserID;
        $bookCopies = BookCopy::myBorrowedBooks($borrowerID);

        return View::make('borrowedBooks',array('bookCopies' => $bookCopies));
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
                $bookCategories = $book->getCachedCategories();

                $copies = $book->getCachedCopies();

                $response = View::make("book",
                    array('book' => $book, 
                        'bookCategories' => $bookCategories,
                        'copies' => $copies));
                return $response;
            }
	    }
    }

    public function addBook()
    {
        $result = FlatBook::addBook(Input::all());

        if ($result['success'])
            return $this->addBookThanks($result['bookID']);
        else
        {
            // Session::put('TransactionMessage',['AddBook',$result]);
            return Redirect::to(URL::previous())->withErrors($result['errors']);
        }
    }

    public function getAdminAddBook()
    {
        return View::make('admin.admin-add-book');
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

        $user = Session::get('loggedInUser');

        $bookCopyID = Input::get('bookCopyID');
        $bookCopy = BookCopy::find($bookCopyID);
        $bookID = $bookCopy->BookID;
        $result = $bookCopy->delete(true);
        if ($result[0])
        {
            if ($result[1]) // book itself deleted, return to my-books
            {
                Session::put('TransactionMessage',['DeleteBook','Book copy deleted.']);   
                return Redirect::route('user-books',$user->Username);
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
        //NOTE: This is a temporary workaround because in production
        //I get incomplete class when calling upon session saved 
        //RegisteredUser
        $registeredUser = RegisteredUser::find(Session::get('loggedInUser')->UserID);

        $result = $registeredUser->editBookInfo(Input::all());


        if ($result['success'])
        {
            if ($result['updated'])
                Session::put('TransactionMessage',['EditBook','Book details updated']);
            else
                Session::put('TransactionMessage',['EditBook','Information sent to Librarian for verification, as mulitiple copies of this book exist in MeULib']);
            return $this->showSingle($bookID);
        }
        else
        {
            return Redirect::to(URL::to('book/'.$bookID))->withErrors($result['errors']);
        }
    }

    public function editBookCopy()
    {
        if (!Session::has('loggedInUser'))
            return Redirect::route('login');

        $bookCopyID = Input::get('bookCopyID');
        $bookID = Input::get('bookID');
        $bookCopy = BookCopy::find($bookCopyID);
        $result = $bookCopy->editSettings(Input::all());
        if ($result['success'])
        {
            Session::put('TransactionMessage',['EditBook','Book settings updated.']);
            return Redirect::route('single-book',$bookID);
        }
        else
            return Redirect::route('single-book',$bookID)->withErrors($result['errors']);

    }


    public function search()
    {
        $s = Input::get('s');
        $result = Librarian::cachedSearch($s);
        return View::make('searchResults', 
            array('books'=>$result,
                'term'=>$s));
    }

    
}
?>