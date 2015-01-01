<?php

class AddBookTest extends TestCase 
{

	public function testValidUser()
	{
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My First Book!');
		$this->assertFalse(FlatBook::addBook($bookDetails)[0]); // should not save
	}

	public function testTitleGiven()
	{
		Session::put('loggedInUser',$this->owner);
		$bookDetails = array('Author1'=>'Me');
		$this->assertFalse(FlatBook::addBook($bookDetails)[0]); // should not save
	}

	public function testAuthorGiven()
	{
        Session::put('loggedInUser',$this->owner);
		$bookDetails = array('Title'=>'My Third Book!');
		$this->assertFalse(FlatBook::addBook($bookDetails)[0]); // should not save
	}

	public function testBasicSaved()
	{
		Session::put('loggedInUser',$this->owner);
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My Real Book!');
		$result = FlatBook::addBook($bookDetails);
		$this->assertTrue($result[0]); // should have saved
		$bookID = $result[1];
		$book = FlatBook::find($bookID);
		$this->assertEquals($bookID, $book->ID);
		$this->assertEquals($bookDetails['Author1'], $book->Author1);
		$this->assertEquals($bookDetails['Title'], $book->Title);
		$bookCopy = BookCopy::where('BookID', '=', $bookID)
								->where('UserID', '=', $this->owner->UserID)
								->count();
		$this->assertEquals($bookCopy,1); 
	}

	public function testFullSaved()
	{
		Session::put('loggedInUser',$this->owner);
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My Full Book!',
						'Author2' => 'My Collaborator',
						'Language1' => 'First Language',
						'Language2' => 'Second Language',
						'SubTitle' => 'More words needed');
		$result = FlatBook::addBook($bookDetails);
		$this->assertTrue($result[0]); // should have saved
		$bookID = $result[1];
		$book = FlatBook::find($bookID);
		$this->assertEquals($bookID, $book->ID);
		$this->assertEquals($bookDetails['Author1'], $book->Author1);
		$this->assertEquals($bookDetails['Title'], $book->Title);
		$this->assertEquals($bookDetails['Author2'], $book->Author2);
		$this->assertEquals($bookDetails['Language1'], $book->Language1);
		$this->assertEquals($bookDetails['Language2'], $book->Language2);
		$this->assertEquals($bookDetails['SubTitle'], $book->SubTitle);
		$bookCopy = BookCopy::where('BookID', '=', $bookID)
								->where('UserID', '=', $this->owner->UserID)
								->count();
		$this->assertEquals($bookCopy,1); 	
	}

	public function testLanguageIDSaved()
	{
		$english = Language::where('LanguageNative','=','English')->first();
		$hindi = Language::where('LanguageNative','=','हिन्दी')->first();
		Session::put('loggedInUser',$this->owner);
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My Full Book!',
						'Author2' => 'My Collaborator',
						'Language1' => 'English',
						'Language2' => 'हिन्दी',
						'SubTitle' => 'More words needed');
		$result = FlatBook::addBook($bookDetails);
		$this->assertTrue($result[0]); // should have saved
		$bookID = $result[1];
		$book = FlatBook::find($bookID);
		$this->assertEquals($bookID, $book->ID);
		$this->assertEquals($english->ID, $book->Language1ID);
		$this->assertEquals($hindi->ID, $book->Language2ID);
	}

}
