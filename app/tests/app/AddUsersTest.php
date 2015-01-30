<?php

class AddUsersTest extends TestCase 
{
	public function testUserSaved()
	{
		// DB::statement('SET NAMES utf8');
		$userData = array('email'=>'catchall@manaskriti.com',
						'name'=>'Someone',
						'locality'=>'Chandni Chowk',
						'city'=>'Ajab Shahar',
						'state'=>'Iowa',
						'country'=>'Inhee Des',
						'username'=>'testuser',
						'password'=>'abracadabra');
		$result = UserAccess::addNew($userData);
		// user should be saved
		$this->assertTrue($result[0]); 
		// retrive user
		$users = User::where('EMail', '=', 'catchall@manaskriti.com')->get();
		$user = $users->first();
		//var_dump($user->toArray());
		$this->assertEquals($user->Country,'Inhee Des');	// country given saved
	}
}
