<?php

class AddUsersTest extends TestCase 
{
	public function testUserSaved()
	{
		// all these code blocks are in the same test because
		// there is a logical flow - no point creating multiple
		// different tests and starting the db from scratch
		// putting this in a code block only to help in code folding

		/* test adding a user */
		{
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

		/* test user activation */
		{
			$tblPrefix = Config::get('database.connections')['mysql']['prefix'];

			$sql = "SELECT ActivationHash FROM ".$tblPrefix."user_access 
				WHERE EMail = :email";
			$result = DB::select($sql,
				array('email'=>'catchall@manaskriti.com'));
			$activationHash = $result[0]->ActivationHash;
			$result = UserAccess::verifyUserEmail('me@you.com',
				$activationHash);
			$this->assertFalse($result);
			$result = UserAccess::verifyUserEmail('catchall@manaskriti.com',
				'qwert');
			$this->assertFalse($result);
			$result = UserAccess::verifyUserEmail('catchall@manaskriti.com',
				$activationHash);
			$this->assertTrue($result);
		}

	}
}
