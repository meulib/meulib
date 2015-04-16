<?php

class BrowseTest extends TestCase 
{
	public function testBookLocationsRetrieved()
	{
		$result = Location::havingBooks();
		//var_dump($result);
		//var_dump(get_class($result));
		//var_dump($result);	// array
		$this->assertEquals(1,count($result));
		$this->assertEquals('Udupi-Manipal',$result->first()->Location);
	}

	public function testBooksByLocationRetrieved()
	{
		Session::put('loggedInUser',$this->owner);
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My Real Book!',
						'Language1'=>'English');
		$result = FlatBook::addBook($bookDetails);
		Session::put('loggedInUser',$this->otherOwner);
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'I Miss You',
						'Language1'=>'English');
		$result = FlatBook::addBook($bookDetails);
		$result = FlatBook::filtered(1);	// udupi-manipal
		$result = $result->getItems();
		//var_dump(get_class($result));	
		//var_dump($result);
		//$result = $result->all();
		$this->assertEquals(2,count($result));	// one from seeder, one added here
		$this->assertEquals('Dennis',$result[0]->Title);
		$this->assertEquals('My Real Book!',$result[1]->Title);
		$this->assertEquals('Me',$result[1]->Author1);

		$result = FlatBook::filtered(2);	// kolkata
		$result = $result->all();
		$this->assertEquals(1,count($result));	// one added here
		$this->assertEquals('I Miss You',$result[0]->Title);
	}
}