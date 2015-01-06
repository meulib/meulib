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
            $books = FlatBook::checked(1)
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