<?php

class BookTest extends TestCase 
{

	// -----------------------------------------
	// -------- ADD BOOK -----------------------
	// -----------------------------------------
	public function testValidUser()
	{
		// test valid user
		$bookDetails = array('Author1'=>'Me',
						'Title'=>'My First Book!');
		$this->assertFalse(FlatBook::addBook($bookDetails)[0]); // should not save

		// test title given
		Session::put('loggedInUser',$this->owner);
		$bookDetails = array('Author1'=>'Me');
		$this->assertFalse(FlatBook::addBook($bookDetails)[0]); // should not save

		// test author given
		$bookDetails = array('Title'=>'My Third Book!');
		$this->assertFalse(FlatBook::addBook($bookDetails)[0]); // should not save

		// test basic saved
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

		// test full saved
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

	// -----------------------------------------
	// -------- BOOK INFO ----------------------
	// -----------------------------------------
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

	// -----------------------------------------
	// -------- DELETE BOOKCOPY-----------------
	// -----------------------------------------
	public function testDeleteBookCopy()
	{
		// create a transaction for book
		$msg = 'May I borrow this book please? When and where can I meet you?';
		$result = Transaction::request($this->borrower->UserID,1,$msg);

		Session::put('loggedInUser',$this->owner);
		$bookCopy = BookCopy::find(1);
		$deleteResult = $bookCopy->delete();
		// should not delete without force = true
		// when active transactions exist
		$this->assertFalse($deleteResult[0]);

		$deleteResult = $bookCopy->delete(true);	
		$this->assertTrue($deleteResult[0]);
		
		$bookCopyAgain = BookCopy::find(1);
		$this->assertFalse(isset($bookCopyAgain));
	}
}