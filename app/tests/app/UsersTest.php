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

	public function testLocationsAsPerUsers()
	{
		$result = Location::getCountriesAsPerUsers();
		//var_dump($result);
		$this->assertEquals(2,count($result));	// one added here
		$this->assertEquals('India',$result[0]->Country);
		$this->assertEquals(2,$result[0]->TotalMembers);
		$this->assertEquals('USA',$result[1]->Country);
		$this->assertEquals(1,$result[1]->TotalMembers);

		$result1 = Location::getCitiesAsPerUsers($result[0]->Country);
		$this->assertEquals(2,count($result1));	// one added here
		$this->assertEquals(1,$result1[0]->TotalMembers);
		$this->assertEquals(1,$result[1]->TotalMembers);		
	}
}
?>