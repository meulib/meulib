<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$filename = pathinfo($_SERVER["SCRIPT_FILENAME"],PATHINFO_FILENAME);
$loggedIn = (isset($_SESSION['UserLoggedIn'])&&$_SESSION['UserLoggedIn']);

echo "<div id='head' style='position:relative;width:100%;'>";
echo "<div id='sitetitle' style='width:50%;'>";
	if ($filename == "index")
		echo "<h1>".WORDING_LIB_TITLE."</h1>";
	else
		echo "<a href='index.php'><h1>".WORDING_LIB_TITLE."</h1></a>";
echo "</div>";
echo "<div id='toplinks' style='width:50%;position:absolute;right:0;top:0;text-align:right'>";
echo "How It Works | Volunteer | Donate | ";
		if ($filename == "vision")
			echo WORDING_VISION_TITLE;
		else
				echo "<a href='".URL_VISION."'>".WORDING_VISION_TITLE."</a>";
		echo " | Team";
if ($loggedIn)
{
	echo "<br/>";
	echo "Hello " . $_SESSION['UserRealName'] . " | <a href='logout.php'>Logout</a>";
}
else
{
	echo "<br/>";
	if ($filename != "index")
	{
		include 'loginBox.php';
	}
}
echo "</div>";
echo "</div>";

echo "<br/>";

echo "<b>".WORDING_LIB_MESSAGE."</b><br/><br/>";

if ($filename == "browse")
	echo WORDING_BROWSE_TITLE;
else
	echo "<a href='".URL_BROWSE."'>".WORDING_BROWSE_TITLE."</a>";

if (!$loggedIn) 
{
	if ($filename != "index") 
	{
		echo " | ";

		if ($filename == "signup")
			echo WORDING_SIGNUP_TITLE;
		else
			echo "<a href='".URL_SIGNUP."'>".WORDING_SIGNUP_TITLE."</a>";
	}
	else
	{
		echo "<br/><br/>" . "<a href='".URL_SIGNUP."'>".WORDING_SIGNUP_TITLE."</a>";
	}
}
else // logged in
{
	echo " | ";

	if ($filename == "messages")
		echo WORDING_MESSAGES_TITLE;
	else
		echo "<a href='".URL_MESSAGES."'>".WORDING_MESSAGES_TITLE."</a>";

	echo " | ";

	if ($filename == "myBooks")
		echo WORDING_MYBOOKS_TITLE;
	else
		echo "<a href='".URL_MYBOOKS."'>".WORDING_MYBOOKS_TITLE."</a>";

	echo " | ";

	if ($filename == "borrowedBooks")
		echo WORDING_MYBORROWEDBOOKS_TITLE;
	else
		echo "<a href='".URL_BORROWED_BOOKS."'>".WORDING_MYBORROWEDBOOKS_TITLE."</a>";
}
?>
<br/><br/>