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

	public function myBookCopies()
	{

	}

	public static function getUserByUsername($username)
	{
		$user = self::where('Username','=',$username)->with('HumanUser')->first();
		return $user;
	}
}
?>