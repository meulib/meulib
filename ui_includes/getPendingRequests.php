<?php
require_once('../classes/Biz.Transactions.php');
require_once('../languages/lang.en.php');

//echo $_SERVER['QUERY_STRING'];

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
$loggedIn = (isset($_SESSION['UserLoggedIn'])&&$_SESSION['UserLoggedIn']);
if ($loggedIn)
{
	$bookCopyID = $_GET["bookCopyID"];

	$transaction = new Transactions();
	// gets the list of borrowers who have requested for a particular item
	$requests = $transaction->getPendingRequests($bookCopyID);
	echo "<br/>";
	if (count($requests)==0)
	{
		echo WORDING_NO_PENDING_REQUESTS;
	}
	else
	{
		echo "<form action='lendBook.php' method='post'>\n";
		echo "<input type='hidden' name='bookCopyID' value=".$bookCopyID.">";
		echo "<b>".WORDING_PENDING_REQUESTS."</b>";
		foreach ($requests as $request)
		{
			echo "<br/>";
			echo "<input type='radio' required name='lendToID' value='".$request['Borrower']."' />\n";
			echo "<label for = 'lendTo'>".$request['FullName']."</label>";
		}
		echo "<br/><input type='submit' name='lend' value='".WORDING_LEND_ACTION."' />";
		echo "</form>";
	}
}

?>