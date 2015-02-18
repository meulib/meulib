<?php

class UsersTest extends TestCase 
{
	public function testGetUser()
	{
		$user = RegisteredUser::getUserByUsername('abra');
		$this->assertNull($user);
		$user = RegisteredUser::getUserByUsername('vaniprogrammer');
		$this->assertInstanceOf('RegisteredUser',$user);
	}
}
?>