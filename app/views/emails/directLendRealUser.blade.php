<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div style="font-size: 12pt">
			<b>{{$owner}} lent you {{$bookName}}<br/>
			on {{ HTML::link(Config::get('app.url'),Config::get('app.name')) }}
			</b><br/>
			<br/>
			Hi {{$borrower}},<br/><br/>
			{{$owner}} has lent you the book {{$bookName}}. Hope you enjoy it!<br/>
			<br/>
			Admin<br/>
			{{Config::get('app.name');}}<br/>
			{{ HTML::link(Config::get('app.url')) }}
		</div>
	</body>
</html>