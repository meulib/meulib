<?php
require_once("classes/Biz.Items.php");
require_once("classes/Biz.Transactions.php");
require_once("classes/constants.php");
require_once("ui_includes/URLS.php");
require_once("languages/lang.en.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$loggedIn = (isset($_SESSION['UserLoggedIn'])&&$_SESSION['UserLoggedIn']);
$userID = $_SESSION['UserID'];

if (!$loggedIn)
{
	// *** THIS URL TO BE FIXED. DO NOT HARD CODE.
	header("Location: "."index.php");
}

function putReplyRow($tranID, $personName, $userID, $toUserID)
{
	echo "<tr id='T".$tranID."'>";
	echo "<td>".WORDING_ME."</td>";
	echo "<td>".$personName."</td>";
	echo "<td colspan='2'>";
	include 'ui_includes/postMessageForm.php';
	echo "</td>";
	echo "</tr></table>";
}

$transactionHelper = new Transactions();
$messages = $transactionHelper->getMessages($userID);

?>

<html>
<head>
</head>
<body>

<?php 
include 'ui_includes/header.php';
?>

<?php
$tid = 0;
$prevName = "";
$prevToUserID = "";
foreach ($messages as $msg)
{
	if ($tid !== $msg['TransactionID'])
	{
		if ($tid !== 0)
		{
			putReplyRow($tid, $prevName, $userID, $prevToUserID);
		}
		$tid = $msg['TransactionID'];
		$prevName = $msg['OtherUserName'];
		$prevToUserID = $msg['OtherUserID'];
		echo "<table border=1 cellspacing=0 cellpadding=5 id='msgsForTran".$tid."'>";
		echo "<tr><td><b>From</b></td><td><b>To</b></td><td><b>Message</b></td><td></td></tr>";
	}
	echo "<tr>";
	if ($msg['FromTo'] == MESSAGE_FROM)
		echo "<td>".WORDING_ME."</td>";
	else
		echo "<td>".$msg['OtherUserName']."</td>";
	if ($msg['FromTo'] == MESSAGE_TO)
		echo "<td>".WORDING_ME."</td>";
	else
		echo "<td>".$msg['OtherUserName']."</td>";
	if ($msg['FromTo'] == MESSAGE_FROM)
		echo "<td style='background-color:99FFFF'>";
	else
		echo "<td style='background-color:CCFF99'>";
	echo $msg['Message']."</td>";
	echo "<td>".WORDING_MESSAGES_REPLY_ACTION."</td>";
	echo "</tr>";
}

putReplyRow($tid, $prevName, $userID, $msg['OtherUserID']);

?>
</body>
</html>