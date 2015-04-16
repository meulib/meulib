<?php 

class BlogGateway extends BaseController
{

	public function homePage()
	{
		$recentPosts = Blog::recentPosts();
		return View::make('blog.home',
		        	array('recentPosts' => $recentPosts));
	}

	public function postPage($postSlug)
	{
		$blogPost = BlogPost::where('Slug',$postSlug)->first();
		return View::make('blog.post',array('blogPost' => $blogPost ));
	}

}