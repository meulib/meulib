<?php

class MessagesTest extends TestCase 
{
	/*public function testRequestPostedSuccessfully()
	{
		// 1 is the book copy id as per the book seeder
		$msg = 'May I borrow this book please? When and where can I meet you?';
		$result = Transaction::request($this->borrowerUser->UserID,1,$msg);
		$this->assertInternalType("int", $result);
		$this->assertGreaterThan(0, $result);
		// more detailed testing for each table required
	}*/

	// when no specific transaction id is specified
	// calling Messages in UI must return all unread message threads
	// if any exist
	public function testGeneralMessagesDisplayed()
	{
		// post a request for a book, that should create a message
		$msg = 'May I borrow this book please? When and where can I meet you?';
		$tranID = Transaction::request($this->borrowerUser->UserID,1,$msg);

		Session::start();
		Session::put('loggedInUser',$this->ownerUser);
		$response = $this->action('GET', 'TransactionController@messages');
		$this->assertViewHas('msgTransactions');
		$transactions = $response->original->getData()['msgTransactions'];
		$msgs = $response->original->getData()['msgs'];
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $transactions);
		
	}
}