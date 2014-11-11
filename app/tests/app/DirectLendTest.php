<?php

class DirectLendTest extends TestCase 
{
	public function testBorrowerDetails()
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
		$this->assertInternalType('int', $result[0]);
	}

}