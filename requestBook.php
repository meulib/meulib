<?php
require_once('classes/Biz.Transactions.php');
require_once('classes/UI.Exceptions.php');

$whereFrom = $_SERVER['HTTP_REFERER'];
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
$loggedIn = (isset($_SESSION['UserLoggedIn'])&&$_SESSION['UserLoggedIn']);
if ($loggedIn)
{
	$userid = $_SESSION['UserID'];
	$bookCopyID = $_POST["bookCopyID"];
	$msg = $_POST['requestMessage'];

	$transaction = new Transactions();
	$result = $transaction->requestItem($userid,$bookCopyID,$msg);

	if ($result)
	{
		$_SESSION['MessageFrom'] = 'Request Book';
		$_SESSION['Message'] = "Request sent successfully.";
	}
	else
	{
		$_SESSION['MessageFrom'] = 'Request Book';
		$_SESSION['Message'] = "There was some error. Request not sent.";
	}
	header("Location: ".$whereFrom);
}
else
{
	header("Location: "."index.php");
}

?>