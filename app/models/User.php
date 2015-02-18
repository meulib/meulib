<?php

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
//	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
//	protected $hidden = array('password', 'remember_token');

//}

class User extends Eloquent {

	protected $table = 'users';
	protected $primaryKey = 'UserID';
	protected $hidden = array('UserID','EMail');

	/*protected function RegistrationDetails()
    {
        return $this->hasOne('RegisteredUser','UserID','UserID');
    }*/

}

?>
