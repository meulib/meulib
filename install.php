<?php
require_once("classes/Biz.CreateTables.php");
require_once("languages/lang.en.php");

$install = new CreateTables();

$msg = $install->createAllTables();

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>

<?php 
foreach ($msg as $m) {
	echo $m."<br/>\n";
}
?>
</body>
</html>