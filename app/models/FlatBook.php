<?php

class FlatBook extends Eloquent {

	use SoftDeletingTrait;

	protected $table = 'books_flat';
	protected $primaryKey = 'ID';
	protected static $rules = array(
            	'Title' => 'required',
	            'Author1' => 'required',
	            'Language1' => 'required'
        	);
	// protected static $cacheKey = Config::get('app.cacheKeys')['flatBook'];

	protected static function boot()
    {
        parent::boot();

        static::updated(function($model)
        {
        	// Log::debug("updated FlatBook event");
            return $model->clearCache($model);
        });

        static::deleting(function($model)
        {
            return $model->clearCache($model);
        });
    }

    private function clearCache($model)
    {
    	$cacheKey = Config::get('app.cacheKeys')['flatBook'].$model->ID;
    	Cache::forget($cacheKey);
    	// Log::debug("cleared cache key ".$cacheKey);
    	return true;
    }

    private function clearCacheCategories()
    {
    	$cacheKey = Config::get('app.cacheKeys')['bookCategories'].$this->ID;
    	Cache::forget($cacheKey);
    	return true;
    }

    public function clearCachedCopies()
    {
    	$cacheKey = Config::get('app.cacheKeys')['bookCopies'].$this->ID;
    	Cache::forget($cacheKey);
    	return true;
    }


	public static function find($id,$columns = array('*'))
	{
		$cacheKey = Config::get('app.cacheKeys')['flatBook'].$id;
		if (Cache::has($cacheKey))
		{
			// Log::debug('FlatBook find from Cache '.$cacheKey);
			return Cache::get($cacheKey);
		}
		else
		{
			// Log::debug('FlatBook find from db, saved in cache '.$cacheKey);
			$flatBook = parent::find($id);
			Cache::put($cacheKey,$flatBook,60);
			return $flatBook;
		}
	}


	// ------------------ RELATIONSHIPS ----------------------

	public function Copies()
	{
		return $this->hasMany('BookCopy', 'BookID', 'ID');
	}

	public function getCachedCopies()
	{
		$cacheKey = Config::get('app.cacheKeys')['bookCopies'].$this->ID;
		log::debug('getCachedCopies for cacheKey '.$cacheKey);
    	return Cache::remember($cacheKey, 60, function() use($cacheKey)
        {
        	Log::debug('getCachedCopies from db, not cacheKey '.$cacheKey);
            return $this->Copies()->get();
        });		
	}

	public function Categories()
    {
        return $this->belongsToMany('Category','book_categories','BookID','CategoryID')->withTimestamps();
    }

    public function getCachedCategories()
    {
    	$cacheKey = Config::get('app.cacheKeys')['bookCategories'].$this->ID;
    	Log::debug('getCachedCategories for cacheKey '.$cacheKey);
    	return Cache::remember($cacheKey, 60, function() use($cacheKey)
        {
        	Log::debug('getCachedCategories from db, not cacheKey '.$cacheKey);
            return $this->Categories()->get();
        });	
    }

	public function MainLanguage()
	{
		return $this->belongsTo('Language', 'Language1ID', 'ID');
	}

	public function SecondaryLanguage()
	{
		return $this->hasOne('Language', 'Language2ID', 'ID');
	}

	// ------------- HELPER FUNCTION ---------------------------

	public function FullTitle()
	{
		$title = $this->Title;
		if (strlen($this->SubTitle)>0)
			$title .= ' : ' . $this->SubTitle;
		return $title;
	}

	// -------------- ACTION FUNCTIONS -----------------------

	public static function addBook($bookDetails)
	{
		$user = NULL;
		if (isset($bookDetails['UserID']))
		{
			$user = User::find($bookDetails['UserID']);
		}
		else
		{
			if (!Session::has('loggedInUser'))
            return array(false,'No user logged in.');

        	$user = Session::get('loggedInUser');
		}
		
        $userID = $user->UserID;
        $userLocation = $user->LocationID;

		// $rules = array(
  //           'Title' => 'required',
  //           'Author1' => 'required'
  //       );
        $validator = Validator::make($bookDetails, self::$rules);
        if ($validator->fails()) 
        {
            return array(false,$validator->messages());
        }

        $coverFilename = "";
        if (isset($bookDetails['book-cover']))
		{
			$uploadResult = FileManager::uploadImage($bookDetails['book-cover'],'book-covers');
			if ($uploadResult['success'])
			{
				// upload successful
				// set info in book record
				$coverFilename = $uploadResult['filename'];
			}
			else
			{
				return $uploadResult;
			}
		}

        $book = new FlatBook;
        $book->Title = $bookDetails['Title'];
        $book->Author1 = $bookDetails['Author1'];
        if (isset($bookDetails['Author2']))
        	$book->Author2 = $bookDetails['Author2'];
        if (isset($bookDetails['Language1']))
        {
        	$book->Language1 = $bookDetails['Language1'];
        	$language1 = Language::where('LanguageNative','=',$bookDetails['Language1'])->first();
        	if ($language1 != NULL)
        		$book->Language1ID = $language1->ID;
        }	        
        if (isset($bookDetails['Language2']))
        {
        	$book->Language2 = $bookDetails['Language2'];
        	$language2 = Language::where('LanguageNative','=',$bookDetails['Language2'])->first();
        	if ($language2 != NULL)
        		$book->Language2ID = $language2->ID;
        }
        if (isset($bookDetails['SubTitle']))
        	$book->SubTitle = $bookDetails['SubTitle'];
        if (strlen($coverFilename)>0)
        	$book->CoverFilename = $coverFilename;
        $book->Checked = 0;

        $result = $book->save();
        if ($result)
        {
        	$bookCopy = new BookCopy;
        	$bookCopy->BookID = $book->ID;
        	$bookCopy->UserID = $userID;
        	$bookCopy->LocationID = $userLocation;
        	if (isset($bookDetails['ForGiveAway']))
        		$bookCopy->ForGiveAway = $bookDetails['ForGiveAway'];
        	$result = $bookCopy->save();
        	if ($result)
        	{
        		if (!Session::has('AddBookAdminMail'))
				{
				    $bodyText = 'New Book Added ' . $userID . ' ' . $user->FullName;
				    $subject = 'New ' . Config::get('app.name') . ' Book';
				    Postman::mailToAdmin($subject,$bodyText);

					Session::put('AddBookAdminMail','sent');
				}     		
	        	return array('success'=>true,'bookID'=>$book->ID);
        	}
        	return array('success'=>false,'errors'=>['Book not saved. DB save error 2.']);
        }
        return array('success'=>false,'errors'=>['Book not saved. DB save error 1.']);
	}

	// $independent: if this is being called from another 
	// already active db transaction then call this with 
	// independent = false
	// then DB begintransaction, commit will not be called here
	// and operations performed here will be part of larger transaction
	// BOOKS ARE SOFT DELETED
	// BOOK-CATEGORY HARD DELETED
	public function delete($independent = true)
	{
		//$this->load('Categories');
		$BookID = $this->ID;
		//$categories = $this->Categories();
		if ($independent)
			DB::beginTransaction();
		try 
		{
			$this->Categories()->detach();	// delete book_categories recs
			$this->clearCacheCategories();
			parent::delete();	// delete itself
		}
		catch (Exception $e)
		{
			if ($independent)
				DB::rollback();
			throw $e;
		}
		if ($independent)
		{
			DB::commit();
			// send admin notification mail
		}

		return $BookID;
	}

	// set 1 or more categories for a book
	public function setCategory($givenCategories)
	{
		// only 1 given category
		if (is_int($givenCategories) && ($givenCategories > 0))
		{
			$this->load('Categories');
			$exists = $this->Categories->contains($givenCategories);
			if ($exists)
				return [true,''];
			
			$foundCategory = Category::find($givenCategories);
			//var_dump('abc');
			//var_dump($foundCategory);
			if (is_null($foundCategory))
				return [false,'Category Not Found'];

			$result = $this->Categories()->attach($givenCategories, array('Suggested' => 1));
			$this->clearCacheCategories();
			return [true,''];
		}

		// multiple categories given
		if (is_array($givenCategories) && (count($givenCategories) > 0))
		{
			$this->load('Categories');
			$existingCategories = $this->Categories->lists('ID');
			$remainingCategories = array_diff($givenCategories,$existingCategories);
			if (count($remainingCategories) == 0)
				return [true,''];

			$foundCategories = Category::whereIn('ID', $remainingCategories)->lists('ID');
			if (count($foundCategories) == 0)
				return [false,'Category Not Found'];
			$result = $this->Categories()->attach($foundCategories, array('Suggested' => 1));
			$this->clearCacheCategories();
			return [true,''];
		}

		return [false,'Incorrect Parameter'];
	}

	// suggest 1 or more categories for a book
	// suggested categories are simply emailed to admin for review
	// not added to db
	public function suggestCategory($categories)
	{
		$byUser = false;
		if (Session::has('loggedInUser'))
		{
			$byUser = true;
	        $user = Session::get('loggedInUser');
		}

		$bodyText = $this->FullTitle() . " | " . 
			$this->Author1 . " | " .
			"BookID: " . $this->ID . " | " .
			"Suggested category: " . $categories . " | " ;
		if ($byUser)
		{
			$bodyText .= "Suggested by: " . $user->userID . " " .
			$user->FullName . " " . $user->City;
		}
		else
		{
			$bodyText .= "Suggested by: Anonymous User";
		}


		// $body = array('body'=>$bodyText);
		$subject = 'New Category Suggested';
		Postman::mailToAdmin($subject,$bodyText);

		/*Mail::queue(array('text' => 'emails.raw'), $body, function($message)
		{
			$message->to(Config::get('mail.admin'))
					->subject('New Category Suggested');
		});*/

		return [true,''];
	}

	public function updateBook($bookDetails)
	{
		$validator = Validator::make($bookDetails, self::$rules);
        if ($validator->fails()) 
        {
            return array('success'=>false,'errors'=>$validator->messages());
        }
		if ($bookDetails['book-cover'])
		{
			$uploadResult = FileManager::uploadImage($bookDetails['book-cover'],'book-covers');
			if ($uploadResult['success'])
			{
				// upload successful
				// set info in book record
				$this->CoverFilename = $uploadResult['filename'];
			}
			else
			{
				return $uploadResult;
			}
		}

		$this->Title = $bookDetails['Title'];
        $this->Author1 = $bookDetails['Author1'];
        if (isset($bookDetails['Author2']))
        	$this->Author2 = $bookDetails['Author2'];
        if (isset($bookDetails['Language1']))
        {
        	$this->Language1 = $bookDetails['Language1'];
        	$language1 = Language::where('LanguageNative','=',$bookDetails['Language1'])->first();
        	if ($language1 != NULL)
        		$this->Language1ID = $language1->ID;
        }	        
        if (isset($bookDetails['Language2']))
        {
        	$this->Language2 = $bookDetails['Language2'];
        	$language2 = Language::where('LanguageNative','=',$bookDetails['Language2'])->first();
        	if ($language2 != NULL)
        		$this->Language2ID = $language2->ID;
        }
        if (isset($bookDetails['SubTitle']))
        	$this->SubTitle = $bookDetails['SubTitle'];
        $this->Checked = 0;

		$this->save();

		return array('success' => true);
	}

	// ------------------ QUERY SCOPES ------------------

	public function scopeMode($query,$mode)
	{
		return $query->whereHas('Copies', function($q) use($mode)
						{
						    $q->where('ForGiveAway', '=', $mode);
						}
					);
	}

	public function scopeLocation($query,$LocationID)
	{
		return $query->whereHas('Copies', function($q) use($LocationID)
						{
						    $q->where('LocationID', '=', $LocationID);
						}
					);
	}

	public function scopeLanguage($query,$LanguageID)
	{
		return $query->where(function ($query) use($LanguageID)
						{
						$query->where('Language1ID','=', $LanguageID)
							->orWhere('Language2ID','=',$LanguageID);
						});

	}

	public function scopeCategory($query,$CategoryID)
	{
		return $query->whereHas('Categories', function($q) use($CategoryID)
				{
					$q->where('CategoryID','=',$CategoryID);
				});
	}

	// retrieve books that have the $checked flag 0 or 1
	public function scopeChecked($query)
	{
		return $query->where('Checked','!=','-1');
	}

	// ------------------- RETRIEVE FUNCTIONS --------------

	public static function filtered($mode='all',$LocationID=0,$LanguageID=0,$CategoryID=0)
	{
        $books = NULL;

        if (!is_numeric($LocationID))
        	$LocationID = 0;
        if (!is_numeric($LanguageID))
        	$LanguageID = 0;

        if ($mode != 'all')
        {
        	if ($mode == 'borrow')
        		$forGiveAway = 0;
        	else
        		$forGiveAway = 1;

        	if (is_null($books))
        		$books = FlatBook::mode($forGiveAway);
        	else
        		$books = $books->mode($forGiveAway);
        }

        if ($LanguageID>0)
		{
			if (is_null($books))
				$books = FlatBook::language($LanguageID);
			else
				$books = $books->language($LanguageID);
		}

		if ($LocationID>0)
		{
			if (is_null($books))
				$books = FlatBook::location($LocationID);
			else
				$books = $books->location($LocationID);
		}

		if ($CategoryID>0)
		{
			if (is_null($books))
				$books = FlatBook::category($CategoryID);
			else
				$books = $books->category($CategoryID);
		}

		// get only checked books
		$books = $books->checked();

		$paginationItemCount = Config::get('view.pagination-itemcount');

		$books = $books->orderBy('Title', 'asc')
            ->orderBy('Author1', 'asc')
			->paginate($paginationItemCount);

		/*$queries = DB::getQueryLog();
		$last_query = end($queries);
		var_dump($last_query);*/

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
		if (!empty($booksIDs))
		{
			$books = FlatBook::whereIn('ID',$booksIDs)
						->orderBy('Title', 'asc')
						->with('Copies')
						->get();
			return $books;
		}
		else
			return false;
	}



}

?>