<?php

class Blog {

	public static function recentPosts()
	{
		$posts = BlogPost::where('Type',1)	// posts
					->orderBy('WhenPublished', 'desc')
					->take(5)->get();
		return $posts;
	}

}

?>