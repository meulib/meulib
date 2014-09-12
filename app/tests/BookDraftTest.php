<?php

class BookDraftTest extends TestCase 
{

	public function testValidUser()
	{
		$bookDraft = new BookDraft;
		$bookDraft->UserID = "WizardUser";
		$bookDraft->Title = "My Book!";
		$bookDraft->Author1 = "Me!";
		$this->assertFalse($bookDraft->save()); // should not save
		$bookDraft->UserID = "OZJM1549672278";
		$this->assertTrue($bookDraft->save()); // should save this time
	}

	public function testTitleGiven()
	{
		$bookDraft = new BookDraft;
		$bookDraft->Author1 = "Me!";
		$this->assertFalse($bookDraft->save()); // should not save
	}

	public function testOneAuthorGiven()
	{
		$bookDraft = new BookDraft;
		$bookDraft->Title = "My Book!";
		$this->assertFalse($bookDraft->save()); // should not save
	}

}
