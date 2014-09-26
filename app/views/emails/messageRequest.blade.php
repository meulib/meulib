<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>A Request For Your Book<br/>
		{{$bookFullTitle}}</h2>

		<div style="font-size: 12pt">
			Hi {{$to}},<br/><br/>
			{{$from}} has requested to borrow your book <b>{{$bookFullTitle}}</b> with the following message:<br/>
			<br/>
			<i><span style="color:blue">{{$msg}}</span></i><br/>
			<br/>
			Click the link below to see the request message and to reply.<br/>
			{{ URL::to('/messages', array($tranID)) }}
			<br/><br/>
			Admin<br/>
			{{Config::get('app.name');}}
			<!-- This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes. -->
		</div>
	</body>
</html>