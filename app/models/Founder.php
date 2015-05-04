<?php

class Founder extends Eloquent {

	protected $table = 'founders';
	protected $primaryKey = 'ID';

	public function UserDetails()
	{
		return $this->hasOne('User','UserID','UserID');
	}

}

?>
