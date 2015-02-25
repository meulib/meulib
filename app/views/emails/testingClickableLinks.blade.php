<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			Hi Anoop ji,<br/><br/>
			Which of these links are clickable, which are not?<br/><br/>
			<b>Link 1</b>: Is this link clickable? I am guessing, it is not.<br/>
			{{ URL::route('home') }}
			<br/><br/>
			<b>Link 2</b>: Is this clickable? I am hoping it is.<br/>
			{{HTML::link(URL::route('how-it-works'), 'How Meulib Works')}}
			<br/><br/>
			<b>Link 3</b>: And this one?<br/>
			{{HTML::link(URL::route('browse'), URL::route('browse'))}}
			<br/>
			<br/><br/>
			Please tell me about all three.<br/>
			<br/><br/>
			Thanks
			<br/><br/>
			Vani
			<!-- This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes. -->
		</div>
	</body>
</html>