<?php

use LaravelBook\Ardent\Ardent;

Validator::extend('user_active','UserAccess@validateUserActive');

class BookDraft extends Ardent {

	protected $table = 'book_drafts';
	//protected $primaryKey = 'UserID';

	public static $rules = array(
		'UserID' => 'required|user_active',
		'Title' => 'required',
		'Author1' => 'required'
	);

}

?>