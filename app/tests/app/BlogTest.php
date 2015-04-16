<?php

class BlogTest extends TestCase 
{
	public function testRecentPosts()
	{
		$result = Blog::recentPosts();
		//var_dump($result);
		$this->assertEquals(1,count($result));
		$this->assertEquals('A Simple Voice Talking To And Of God',$result->first()->Title);
	}


}