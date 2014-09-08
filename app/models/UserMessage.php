<?php

class UserMessage extends Eloquent {

	protected $table = 'messages2';
	protected $primaryKey = 'ID';

	public function User()
	{
		return $this->hasOne('User', 'UserID', 'UserID');
	}

	public function OtherUser()
	{
		return $this->hasOne('User', 'UserID', 'OtherUserID');
	}
}

?>