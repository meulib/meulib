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
	$lendToID = $_POST['lendToID'];

	$transaction = new Transactions();
	$result = $transaction->lendItem($userid,$bookCopyID,$lendToID);

	if ($result)
	{
		$_SESSION['MessageFrom'] = 'Lend Book';
		$_SESSION['Message'] = WORDING_LEND_SUCCESS;
	}
	else
	{
		$_SESSION['MessageFrom'] = 'Lend Book';
		$_SESSION['Message'] = WORDING_LEND_FAILURE;
	}
	header("Location: ".$whereFrom);
}
else
{
	header("Location: "."index.php");
}

?>