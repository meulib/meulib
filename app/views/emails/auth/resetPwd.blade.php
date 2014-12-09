<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset</h2>

		<div>
			Hi {{$name}},<br/><br/>
			Please click this link to reset your {{Config::get('app.name');}} password:<br/>
			{{ URL::to('reset-password', array($id,$resetCode)) }}
			<br/>
			Note: This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.<br/>
			<br/>
			Admin<br/>
			{{Config::get('app.name');}}
		</div>
	</body>
</html>