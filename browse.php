<?php
require_once("classes/Biz.Items.php");
require_once("ui_includes/URLS.php");
require_once("languages/lang.en.php");

$bizItems = new Items();

$items = $bizItems->getItems();

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>

<?php 
include 'ui_includes/header.php';
?>
<?php 

if ($items)
{
	foreach ($items as $item) 
	{
		$title = "<li> <a href='" . URL_BOOK_DETAIL . $item['BookID'] . "'>" . $item['Title'] . 
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
		echo "<br/>";
	}
}
?>
</body>
</html>