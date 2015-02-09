<?php

class FlatBook extends Eloquent {

	use SoftDeletingTrait;

	protected $table = 'books_flat';
	protected $primaryKey = 'ID';

	// ------------------ RELATIONSHIPS ----------------------

	public function Copies()
	{
		return $this->hasMany('BookCopy', 'BookID', 'ID');
	}

	public function Categories()
    {
        return $this->belongsToMany('Category','book_categories','BookID','CategoryID')->withTimestamps();
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
		if (!Session::has('loggedInUser'))
            return array(false,'No user logged in.');

        $user = Session::get('loggedInUser');
        $userID = $user->UserID;
        $userLocation = $user->LocationID;

		$rules = array(
            'Title' => 'required',
            'Author1' => 'required'
        );
        $validator = Validator::make($bookDetails, $rules);
        if ($validator->fails()) 
        {
            return array(false,$validator->messages());
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
        $book->Checked = 0;

        $result = $book->save();
        if ($result)
        {
        	$bookCopy = new BookCopy;
        	$bookCopy->BookID = $book->ID;
        	$bookCopy->UserID = $userID;
        	$bookCopy->LocationID = $userLocation;
        	$result = $bookCopy->save();
        	if ($result)
        	{
        		if (!Session::has('AddBookAdminMail'))
				{
				    $bodyText = 'New Book Added ' . $userID . ' ' . $user->FullName;
				    $subject = 'New ' . Config::get('app.name') . ' Book';
				    AppMailer::MailToAdmin($subject,$bodyText);

					/*Mail::queue(array('text' => 'emails.raw'), $body, function($message)
					{
						$message->to(Config::get('mail.admin'))
								->subject('New ' . Config::get('app.name') . ' Book');
					});*/
					Session::put('AddBookAdminMail','sent');
				}     		
	        	return array(true,$book->ID);
        	}
        	return array(false,'Book not saved. DB save error 2.');
        }
        return array(false,'Book not saved. DB save error 1.');
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
		AppMailer::MailToAdmin($subject,$bodyText);

		/*Mail::queue(array('text' => 'emails.raw'), $body, function($message)
		{
			$message->to(Config::get('mail.admin'))
					->subject('New Category Suggested');
		});*/

		return [true,''];
	}

	// ------------------ QUERY SCOPES ------------------

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

	public static function filtered($LocationID=0,$LanguageID=0,$CategoryID=0)
	{
        $books = NULL;

        if (!is_numeric($LocationID))
        	$LocationID = 0;
        if (!is_numeric($LanguageID))
        	$LanguageID = 0;

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

	public static function search($searchTerm)
	{
		// searches only book title right now
		// does not order result by relevance

		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];

		$sql = "SELECT EntityID, MATCH(Phrase) AGAINST(:term1 IN BOOLEAN MODE) score FROM ".$tblPrefix."search_tbl 
			WHERE MATCH(Phrase) AGAINST(:term2 IN BOOLEAN MODE) order by EntityID asc limit 0,30";
		$bookIDsWithScore = DB::select($sql,array('term1'=>$searchTerm,'term2'=>$searchTerm));

		$bookIDsA = array_map(function($val)
					{
					    return $val->EntityID;
					}, $bookIDsWithScore);
		$books = FlatBook::select('ID','Title','Author1')
				->orderBy('ID','asc')
				->whereIn('ID', $bookIDsA)
				->get();
		return $books->toArray();
		
	}

/*
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
						->orderBy('Title', 'asc')
						->with('Copies')
						->get();
			return $books;
		}
		else
		{
			return false;
		}
	}*/

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

	// ------------------- MAINTENANCE --------------

	public static function updateSearchTbl()
	{
		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];

		// which books are already in search tbl
		$existingIDs = DB::table('search_tbl')->select('EntityID')->distinct()->where('EntityType',1)->lists('EntityID');
		if (count($existingIDs) == 0)
			$existingIDs = [0];
		// which ids are not in search tbl
		$newIDs = DB::table('books_flat')->select('ID')->whereNotIn('ID', $existingIDs)->lists('ID');
		$newIDsString = implode(",", $newIDs);
		// insert data abt new books into search tbl
		$sql = "insert into ".$tblPrefix."search_tbl (EntityID,Phrase,EntityType) select ID,Title,1 from ".$tblPrefix."books_flat where ID in (".$newIDsString.")";
		// var_dump($sql);
		$result = DB::statement($sql);
		return $result;
	}

}

?>