<?php

class Category extends Eloquent {

	protected $table = 'categories';
	protected $primaryKey = 'ID';

	public function Books()
    {
        return $this->belongsToMany('FlatBook','book_categories','CategoryID','BookID')->withTimestamps();
    }

}

?>