<?php
$resetLink = URL::to('reset-password', array($id,$resetCode));
?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<b>Password Reset</b>

		<div>
			Hi {{$name}},<br/><br/>
			Please click this link to reset your {{Config::get('app.name');}} password:<br/>
			{{HTML::link($resetLink, $resetLink)}}
			<br/>
			Note: This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.<br/>
			<br/>
			Admin<br/>
			{{Config::get('app.name');}}
		</div>
	</body>
</html>