<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
Hi {{$userHumanName}},<br/><br/>
Welcome to {{Config::get('app.name');}}, the public library by me and you. Its good to have you here!<br/>
<br/>
Here are some things you could do to start<br/>
- <b>Request To Borrow A Book</b><br/>
Go to {{HTML::link(URL::route('browse'), 'Browse Collection')}} and click on the book that interests you. It will show you the places where owners have offered to lend it. You can send a request message to the owner to borrow the book.<br/>
<br/>
If {{Config::get('app.name');}} is not in your city yet, you can start it! You have the power! Which brings us to the next thing -<br/>
<br/>
- <b>Add Books That You Are Willing To Lend</b><br/>
Go to <b>My Books</b> (it comes up in the menu after you login) and add books that you are willing to lend. Expand our unlimited public library.<br/>
<br/>
If you have any questions or suggestions please feel free to write to {{HTML::link(URL::to('/contact-admin'), 'support')}} or to email me (simply reply to this email). The {{HTML::link(URL::route('how-it-works'), 'How It Works') }} and {{HTML::link(URL::route('faq'), 'Frequently Asked Questions')}} pages may also help.<br/>
<br/>
Have a nice day<br/>
<br/>
{{Config::get('mail.fromHuman')}}<br/>
( {{Config::get('app.name')}} Admin)

	</body>
</html>