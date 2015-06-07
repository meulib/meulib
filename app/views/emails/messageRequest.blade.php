<?php
$replyLink = URL::to('/messages', array($tranID));
?>
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
			{{HTML::link($replyLink, 'Click this link to see the request and to reply.')}}
			<br/><br/>
			Admin<br/>
			{{Config::get('app.name');}}
		</div>
	</body>
</html>