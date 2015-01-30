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
}