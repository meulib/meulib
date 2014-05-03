<?php
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

$bizItems = new Transactions();

$items = $bizItems->getItemsByBorrower($userID);

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<script type="text/javascript" src="ui_includes/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="ui_includes/myBooks.js"></script>
</head>
<body>

<?php 
include 'ui_includes/header.php';
?>
<?php 

$copyCount = 0;
foreach ($items as $item) 
{
	$copyCount++;

	$title = "<li> <a href='" . URL_BOOK_DETAIL . $item['ItemID'] . "'>" . $item['Title'] . 
		((strlen($item['SubTitle'])>0) ? ": ".$item['SubTitle'] : "") . "</a>";
	$author = "";
	if ($item['Author1ID']>0) 
	{
		$author = " " . WORDING_AUTHOR_BY . " " . $item['Author1'] . "</a>";
		if ($item['Author2ID']>0) 
		{
			$author .= ", " . $item['Author2'] . "</a>";
		}
	}
	echo $title . $author;
	echo "<br/>\n";
	$status = array_search($item['Status'], $BOOK_COPY_STATUS);
	echo $status;
	echo "<br/><br/>\n";
}

?>
</body>
</html>