<?php

class AdditionalBookInfoTest extends TestCase 
{
	public function testSetCategory()
	{
		Session::put('loggedInUser',$this->owner);
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My Expanded Book!');
		$result = FlatBook::addBook($bookDetails);
		$bookID = $result[1];
		$book = FlatBook::find($bookID);

		$result = $book->setCategory(768);	// wrong category
		$this->assertFalse($result[0]);	// should not save
		//var_dump($result[1]);	// error msg

		$result = $book->setCategory(1);	// correct category
		$this->assertTrue($result[0]);	// should save

		$result = $book->setCategory(1);	// give again
		$this->assertTrue($result[0]);	// no issue

		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My Multifaceted Book!');
		$result = FlatBook::addBook($bookDetails);
		$bookID = $result[1];
		$book = FlatBook::find($bookID);

		$result = $book->setCategory([5,10]); // test multiple save, wrong category
		$this->assertFalse($result[0]);	// should not save
		//var_dump($result[1]);	// error msg

		$result = $book->setCategory([1,2]); // test multiple save
		$this->assertTrue($result[0]);

		$result = $book->setCategory([1,2]);
		$this->assertTrue($result[0]);
	}

	public function testSuggestCategory()
	{
		Session::put('loggedInUser',$this->owner);
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My New Type Book!');
		$result = FlatBook::addBook($bookDetails);
		$bookID = $result[1];
		$book = FlatBook::find($bookID);

		$result = $book->suggestCategory('History');
		$this->assertTrue($result[0]);
	}
}