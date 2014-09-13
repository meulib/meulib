<?php

class AddBookTest extends TestCase 
{

	public function testValidUser()
	{
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My First Book!');
		$this->assertFalse(FlatBook::addBook($bookDetails)); // should not save
	}

	public function testTitleGiven()
	{
		$loggedInUser = UserAccess::find("OZJM1549672278");
        Session::put('loggedInUser',$this->validUser);
		$bookDetails = array('Author1'=>'Me');
		$this->assertFalse(FlatBook::addBook($bookDetails)); // should not save
	}

	public function testAuthorGiven()
	{
		$loggedInUser = UserAccess::find("OZJM1549672278");
        Session::put('loggedInUser',$this->validUser);
		$bookDetails = array('Title'=>'My Third Book!');
		$this->assertFalse(FlatBook::addBook($bookDetails)); // should not save
	}

}
