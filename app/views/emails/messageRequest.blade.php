<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div style="font-size: 12pt">
			<b>A Request For Your Book<br/>
			{{$bookFullTitle}}</b><br/>
			<br/>
			Hi {{$to}},<br/><br/>
			{{$from}} has requested your book <b>{{$bookFullTitle}}</b> with the following message:<br/>
			<br/>
			<i><span style="color:blue">{{$msg}}</span></i><br/>
			<br/>
			Click the link below to see the request message and to reply.<br/>
			{{ URL::to('/messages', array($tranID)) }}
			<br/><br/>
			Admin<br/>
			{{Config::get('app.name');}}
		</div>
	</body>
</html>