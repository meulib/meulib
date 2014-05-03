<?php
require_once("classes/Biz.Items.php");
require_once("ui_includes/URLS.php");
require_once("languages/lang.en.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$loggedIn = (isset($_SESSION['UserLoggedIn'])&&$_SESSION['UserLoggedIn']);

//-------- TODO ------------ check that b (book id) is set
$itemID = $_GET["b"];
// ------- TODO ------------ check $itemID is a number
$bizItems = new Items();
$itemDetail = $bizItems->getItemDetail($itemID);
$itemCopies = $bizItems->getItemCopies($itemID); 

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<script type="text/javascript">
	var shownDiv = "";
	function showDiv(divid)
	{
		if (shownDiv != "")
			document.getElementById(shownDiv).style.display = "none";

		document.getElementById(divid).style.display = "block";
		shownDiv = divid;
	}
	</script>
</head>
<body>
<?php 
include 'ui_includes/header.php';
?>
<?php 

if (isset($_SESSION['MessageFrom']) && ($_SESSION['MessageFrom'] == 'Request Book'))
{
	echo "<b>".$_SESSION['Message']."</b>";
	$_SESSION['Message'] = null;
	$_SESSION['MessageFrom'] = null;
	echo isset($_SESSION['MessageFrom']);
	echo "<br/><br/>";
}	

$title = $itemDetail['Title'] . ((strlen($itemDetail['SubTitle'])>0) ? ": ".$itemDetail['SubTitle'] : "");
$author = "";
$authorCnt = 0;
if ($itemDetail['Author1ID']>0) 
{
	$authorCnt = 1;
	$author = " " . $itemDetail['Author1'];
	if ($itemDetail['Author2ID']>0) 
	{
		$authorCnt = 2;
		$author .= ", " . $itemDetail['Author2'];
	}
}
echo "<b>".$title."</b><br>";
if ($authorCnt>0)
{
	echo $author;
	if ($authorCnt == 1)
		echo " (".WORDING_AUTHOR1.")";
	else
		echo " (".WORDING_AUTHORS.")";
}

$itemCopiesCnt = count($itemCopies);
echo "<br/><br/>";
echo WORDING_BOOK_COPIES . " (" . $itemCopiesCnt . ")";
echo "<br><br><b>".WORDING_LENDERS."</b><br/><br/>";
$copyCount = 0;
foreach ($itemCopies as $itemCopy) 
{
	$copyCount++;
	if ($loggedIn)
		$lender = $itemCopy['FullName'] . ": " . $itemCopy['City'] . ", " . $itemCopy['Locality'];
	else
		$lender = $itemCopy['City'] . ", " . $itemCopy['Locality'];
	echo "<b>".$lender."</b>";
	echo "<br/>";
	$lenderDetail = "Lent books " . $itemCopy['LendingCount'] . " times. Lender Ranking: " 
		. $itemCopy['LenderRanking'] . ". Ranked " . $itemCopy['LenderRankingCount'] . " times";
	echo $lenderDetail;
	echo "<br/>";
	if ($loggedIn)
	{
		if ($_SESSION['UserID'] == $itemCopy['UserID'])
			echo "That is Me! :-)";
		else
		{
			$bookCopyID = $itemCopy['ID'];
			echo "<a href='#' onclick=\"showDiv('requestBook".$copyCount."');\">".WORDING_REQUESTBOOK_ACTION."</a><br/>";
			include 'ui_includes/itemRequest.php';
		}
		echo "<br/>";
	}
	echo "<br/>";
}
?>
</body>
</html>