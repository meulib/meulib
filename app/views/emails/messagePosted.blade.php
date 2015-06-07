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
			<b>A Message For You regarding<br/>
			{{$bookFullTitle}}</b>
			<br/><br/>
			Hi {{$to}},<br/><br/>
			{{$from}} has sent you a message regarding the book <b>{{$bookFullTitle}}</b>:<br/>
			<br/>
			<i><span style="color:blue">{{$msg}}</span></i><br/>
			<br/>
			{{HTML::link($replyLink, 'Click this link to reply to '.$from)}}
			<br/><br/>
			Admin<br/>
			{{Config::get('app.name');}}
		</div>
	</body>
</html>