<?php

// original model name UserAccess
// attempt to name classes more sensibly
class RegisteredUser extends Eloquent 
{

	protected $table = 'user_access';
	public $incrementing = false;
	protected $primaryKey = 'UserID';

	// relationship definition - has one
	public function HumanUser()
    {
        return $this->hasOne('User','UserID','UserID');
    }

    // relationship definition - has many
	public function BookCopies()
	{
		return $this->hasMany('BookCopy', 'UserID', 'UserID');
	}

	// relationship definition.
	public function Books()
	{
		return $this->belongsToMany('FlatBook','bookcopies','UserID','BookID')->withTimestamps();
	}

	// returns ordered by book title - which is not so easy with
	// the BookCopies relationship
	public function myBookCopies()
	{
		$query = BookCopy::with('Book')
			->where('UserID', $this->UserID)
			->join('books_flat', 'bookcopies.BookID', '=', 'books_flat.ID')
			->select('books_flat.ID as BookID','bookcopies.ID as BookCopyID',
				'Title','SubTitle','Author1','Author2','Status','LentOutDt');
		$query->orderBy('Status','desc')
			->orderBy('LentOutDt','asc');
		$query->orderBy('books_flat.Title', 'ASC');
		return $query;
			
	}

	public static function getUserByUsername($username)
	{
		$user = self::where('Username','=',$username)->with('HumanUser')->first();
		return $user;
	}

	public function editBookInfo($newBookInfo)
	{
		// verify book owned by user
		$book = FlatBook::find($newBookInfo['bookID']);
		$originalData = $book->toJson();
		$copies = $book->Copies;
		$copies = $copies->keyBy('UserID');
		if (!isset($copies[$this->UserID]))
			return [false, 'Unauthorized'];

		if (count($copies) == 1)
		{
			$updateResult = $book->updateBook($newBookInfo);

			// email admin
			if ($updateResult['success'])
			{
				$bodyText = $this->UserID . ' ' . $this->HumanUser->FullName .
					' ORIGINAL DATA: ' . $originalData .
					' NEW DATA: ' . json_encode($newBookInfo);
			    $subject = 'Book Edited';
			    Postman::mailToAdmin($subject,$bodyText);
			    return array('success' => true, 'updated' => true );
			}
			else
			{
				return $updateResult;
			}
			
		}
		else
		{
			// TODO: image needs to be saved and kept if book cover included, 
			// else it will get lost I think
			$bodyText = $this->UserID . ' ' . $this->HumanUser->FullName .
				' ORIGINAL DATA: ' . $originalData->toJson() .
				' NEW DATA: ' . json_encode($newBookInfo);
		    $subject = 'Book Edited';
		    Postman::mailToAdmin($subject,$bodyText);

		    return array('success' => true, 'updated' => false );
		}
	}

	public function editMyPreferences($preferencesData)
	{
		
	}
}
?>