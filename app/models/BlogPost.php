<?php

class BlogPost extends Eloquent {

	protected $table = 'blg_post';
	protected $primaryKey = 'PostID';

	public function nicePublishedDate()
	{
		return TimeKeeper::niceDate($this->WhenPublished);
	}

}