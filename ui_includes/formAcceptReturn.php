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
	$borrower = $transaction->getBorrowerDetails($bookCopyID);
	echo "<br/>";
	if (!$borrower)
	{
		echo "There was some error";
	}
	else
	{
		echo "<form action='returnBook.php' method='post'>\n";
		echo "<input type='hidden' name='bookCopyID' value=".$bookCopyID.">";
		echo WORDING_RETURN_GETCONFIRMATION."<br/>";
		echo "<input type='checkbox' name='returnFromID' value='".$borrower['Borrower']."'>\n";
		echo "<label for='returnFromID'>".$borrower['FullName']."</label>";
		echo "<br/><input type='submit' name='return' value='".WORDING_ACCEPT_RETURN_ACTION."' />";
		echo "</form>";
	}
}

?>