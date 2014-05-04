<?php
$filename = pathinfo($_SERVER["SCRIPT_FILENAME"],PATHINFO_FILENAME);
if ($filename == 'index')
{
	?>
<b>Login</b><br/>
<form method="post" action="index.php" name="loginform">
	<label for="user_name"><?php echo WORDING_USERNAME; ?></label>
	<input id="user_name" type="text" name="user_name" required /><br/>
	<label for="user_password"><?php echo WORDING_PASSWORD; ?></label>
	<input id="user_password" type="password" name="user_password" autocomplete="off" required /><br/>
	<input type="submit" name="login" value="<?php echo WORDING_LOGIN; ?>" />
</form>

<a href="password_reset.php"><?php echo WORDING_FORGOT_MY_PASSWORD; ?></a>
<?php
}
else
{
	?>
<form method="post" action="index.php" name="loginform">
	<label for="user_name"><?php echo WORDING_USERNAME; ?></label>
	<input id="user_name" type="text" name="user_name" required /> 
	<label for="user_password"><?php echo WORDING_PASSWORD; ?></label>
	<input id="user_password" type="password" name="user_password" autocomplete="off" required /> 
	<input type="submit" name="login" value="<?php echo WORDING_LOGIN; ?>" />
</form>	
<?php } ?>