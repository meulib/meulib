<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>New Account Verification</h2>

		<div>
			Hi {{$name}},<br/><br/>
			Please click this link to verify your email and activate your account:<br/>
			{{ URL::to('account/activate', array($id,$verificationCode)) }}
			<br/><br/>
			Admin<br/>
			{{Config::get('app.name');}}
			<!-- This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes. -->
		</div>
	</body>
</html>