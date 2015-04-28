<?php

class TransactionTest extends TestCase 
{
	public function testRequestNLendNReturn()
	{
		// 1 is the book copy id as per the book seeder
		$msg = 'May I borrow this book please? When and where can I meet you?';
		$result = Transaction::request($this->borrower->UserID,1,$msg);
		$this->assertInternalType("int", $result);
		$this->assertGreaterThan(0, $result);
		// more detailed testing for each table required

		// test lending requested item
		$lendResult = 0;
		$lendResult = Transaction::lend($this->owner->UserID,1,$this->borrower->UserID);
		$this->assertInternalType("int", $lendResult);
		$this->assertGreaterThan(0, $lendResult);

		// test return of item
		$tran = Transaction::find($lendResult);
		$returnResult = $tran->returnItem();
		$this->assertInternalType("int", $returnResult);
		$this->assertGreaterThan(0, $returnResult);		
	}

	public function testDirectLending()
	{
		$result = Transaction::lendDirect($this->owner->UserID,1,"");
		$this->assertFalse($result[0]);	// borrower name not given

		$result = Transaction::lendDirect($this->owner->UserID,1,"Shiv");
		$this->assertFalse($result[0]);	// neither email or phone given

		$result = Transaction::lendDirect($this->owner->UserID,1,"Shiv",'abc');
		$this->assertFalse($result[0]);	// email not correct format

		$result = Transaction::lendDirect($this->owner->UserID,1,"Shiv",null,'12er');
		$this->assertFalse($result[0]);	// phone not correct format

		$result = Transaction::lendDirect($this->owner->UserID,1,"Shiv",'where.are.you@manaskriti.com');
		$this->assertInternalType('int', $result[0]); // success

		// test return of item
		$tran = Transaction::find($result[0]);
		$returnResult = $tran->returnItem();
		$this->assertInternalType("int", $returnResult);
		$this->assertGreaterThan(0, $returnResult);
	}

	public function testRequestNGiveAway()
	{
		// 1 is the book copy id as per the book seeder
		$msg = 'May I take away this book please? When and where can I meet you?';
		$result = Transaction::request($this->borrower->UserID,1,$msg);
		$this->assertInternalType("int", $result);
		$this->assertGreaterThan(0, $result);
		// more detailed testing for each table required

		// test lending requested item
		$result = 0;
		$result = Transaction::giveAway($this->owner->UserID,1,$this->borrower->UserID);
		$this->assertTrue($result['success']);

		// check the book copy is indeed now the new owner's
		$itemCopy = BookCopy::find(1);
		$this->assertEquals($this->borrower->UserID,$itemCopy->UserID);	// book is now new owner's
		$this->assertEquals(0,$itemCopy->ForGiveAway);
	}

	public function testDirectGiveAwayProspectiveUser()
	{

		$result = Transaction::giveAwayDirect($this->owner->UserID,1,'');
		$this->assertFalse($result['success']);	// borrower name not given

		$result = Transaction::giveAwayDirect($this->owner->UserID,1,"Shiv");
		$this->assertFalse($result['success']);	// neither email or phone given

		$result = Transaction::giveAwayDirect($this->owner->UserID,1,"Shiv",'abc');
		$this->assertFalse($result['success']);	// email not correct format

		$result = Transaction::giveAwayDirect($this->owner->UserID,1,"Shiv",null,'12er');
		$this->assertFalse($result['success']);	// phone not correct format

		$result = Transaction::giveAwayDirect($this->owner->UserID,1,"Shiv",'where.are.you@manaskriti.com');
		$this->assertFalse($result['success']); // item not getting deleted because user not logged in

		Session::put('loggedInUser',$this->owner);
		$result = Transaction::giveAwayDirect($this->owner->UserID,1,"Shiv",'where.are.you@manaskriti.com');
		$this->assertTrue($result['success']);

		// check book deleted from system
		$itemCopy = BookCopy::find(1);
		$this->assertNull($itemCopy);	// bookcopy not found
	}

	/*public function testDirectGiveAwayExistingUser()
	{
		$result = Transaction::giveAwayDirect($this->owner->UserID,1,
			$this->borrower->FullName,$this->borrower->EMail);
		$this->assertTrue($result['success']); // success

		// check book deleted from system
		$itemCopy = BookCopy::find(1);
		$this->assertEquals($this->borrower->UserID,$itemCopy->UserID);	// book is now new owner's
	}*/
}