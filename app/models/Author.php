<?php

class Author extends Eloquent {

	protected $primaryKey = 'ID';
	public function authors()
	{
		return $this->hasMany('Author')
	}
}

?>