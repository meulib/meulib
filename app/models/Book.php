<?php

class Book extends Eloquent {

	protected $primaryKey = 'ID';
	public function authors()
	{
		return $this->hasMany('Author');
	}
}

?>