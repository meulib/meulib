{{--

VARIABLES NEEDED
$firstOwner - giver's name
$to - recepient's name
$bookName
$firstOwnerUsername - first owner's system username for link to first owner's collection

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
			{{$firstOwner}} has recorded giving away the book {{$bookName}} to you on 
			{{ HTML::link(Config::get('app.url'), Config::get('app.name')) }}. 
			We hope it is useful for you.<br/>
			<br/>
			On {{ HTML::link(Config::get('app.url'), Config::get('app.name')) }} you too can manage your books. You can also <br/>
			* borrow / take-away {{ HTML::link(route('user-books', $firstOwnerUsername), 
				'other books from '.$firstOwner); }} and others<br/>
			* offer books of your own to lend to people<br/>
			* record and track books you lend
			<br/><br/>
			{{ HTML::link(Config::get('app.url'), 'Come have a look.') }}
			<br/><br/>
			Admin<br/>
			<b>{{ HTML::link(Config::get('app.url'),
				Config::get('app.name').': '.Config::get('app.tag_line')) }}</b>
		</div>
	</body>
</html>