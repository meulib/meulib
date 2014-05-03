<?php
require_once('classes/UI.User.php');
require_once("languages/lang.en.php");
require_once("ui_includes/URLS.php");
require_once('config.php');
require_once('libraries/PHPMailer.php');

$entryGate = new UIUser();

?>
<html>
<body>
<?php 
include 'ui_includes/header.php';

if (!(isset($_SESSION['UserLoggedIn'])&&$_SESSION['UserLoggedIn']))
{
    include("ui_includes/loginBox.php");
}
?>
</body>
</html>