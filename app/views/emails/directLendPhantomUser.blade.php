<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{$owner}} lent you {{$bookName}}<br/>
		on {{Config::get('app.name');}}</h2>

		<div style="font-size: 12pt">
			Hi {{$borrower}},<br/><br/>
			{{$owner}} has lent you the book <b>{{$bookName}}</b> and recorded it on {{Config::get('app.name');}}. Hope you enjoy it!<br/>
			<br/>
			On {{Config::get('app.name');}}, you can also <br/>
			* borrow other books from {{$owner}} and others<br/>
			* offer books of your own to lend to people<br/>
			* record and track books you lend
			<br/><br/>
			We invite you to come and have a look:<br>
			<a href="'"{{Config::get('app.url');}}"'">{{Config::get('app.url');}}</a>
			<br/><br/>
			Admin<br/>
			{{Config::get('app.name');}}<br/>			
			<!-- This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes. -->
		</div>
	</body>
</html>