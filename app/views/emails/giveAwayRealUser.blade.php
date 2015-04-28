{{--

VARIABLES NEEDED
$firstOwner - giver's name
$to - recepient's name
$bookName
$toUsername - recepient's system username for link to recepient's collection

--}}

<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div style="font-size: 12pt">
			<b>{{$firstOwner}} gave  {{$bookName}} to you.</b><br/><br/>

			Hi {{$to}},<br/><br/>
			{{$firstOwner}} has recorded giving away the book {{$bookName}} to you. 
			We hope it is useful for you.<br/>
			<br/>
			The book is now in your 
			{{ HTML::link(route('user-books', $toUsername), 
				Config::get('app.name').' collection here'); }}. <br/><br/>
			Admin<br/>
			<b>{{ HTML::link(Config::get('app.url'),
				Config::get('app.name').': '.Config::get('app.tag_line')) }}</b>
		</div>
	</body>
</html>