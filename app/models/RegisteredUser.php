<?php

// original model name UserAccess
// attempt to name classes more sensibly
class RegisteredUser extends Eloquent 
{

	protected $table = 'user_access';
	public $incrementing = false;
	protected $primaryKey = 'UserID';

	public function HumanUser()
    {
        return $this->hasOne('User','UserID','UserID');
    }

	public function BookCopies()
	{
		return $this->hasMany('BookCopy', 'UserID', 'UserID');
	}

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
}
?>