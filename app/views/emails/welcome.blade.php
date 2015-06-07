<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
Hi {{$userHumanName}},<br/><br/>
Welcome to {{Config::get('app.name');}}, the book garden grown by me and you. Its good to have you here!<br/>
<br/>
There are many things to do in this garden -<br/>
<br/>
<b>* Request To Borrow / Take A Book</b><br/>
{{HTML::link(URL::route('browse'), 'Browse the collection')}} It will show you the places where owners have offered to lend it. You can send a request message to the owner to borrow the book. Many books are for give-away instead of lending, i.e. you can take them, but do not need to return.<br/>
<br/>
Even if the book is not in your city, you may still send a request. The owner may be willing to lend / give it to you. You must pay for postage.<br/>
<br/>
<b>* Add Books That You Want To Lend / Give-Away</b><br/>
Go to <b>MyBooks</b> (it comes up in the menu after you login) and add your books that you want to lend or give-away. Expand your public library!<br/>
<br/>
<b>* Join The MeULib Forum</b><br/>
{{HTML::link('https://www.facebook.com/groups/meulib/', 'Join the MeULib Forum on Facebook')}} and interact with other MeULib members. This will enable you to lend and borrow from them more easily. Share what you love about books with others.<br/>
<br/>
As a MeULib member, you will receive an email from MeULib at the end of every month with news of what new has happened at MeULib. Watch out for it at the end of the month! If you use GMail, it might go into your promotions tab. Do move it to your Inbox and ask GMail to deliver it to your inbox, if you prefer.<br/>
<br/>
If you have any questions or suggestions please feel free to {{HTML::link(URL::to('/contact-admin'), 'write to support')}} or to email (simply reply to this email). The {{HTML::link(URL::route('how-it-works'), 'How It Works') }} and {{HTML::link(URL::route('faq'), 'Frequently Asked Questions')}} pages may also help.<br/>
<br/>
Once again, a very warm welcome to your own book garden!<br/>
<br/>
Have a nice day!<br/>
<br/>
{{Config::get('mail.fromHuman')}}<br/>
(Your {{Config::get('app.name')}} Gardener)

	</body>
</html>