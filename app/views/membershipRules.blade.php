
@extends('templates.base')

@section('content')

<div class='contentDiv' style='max-width:700px'>
	<div class='pageTitle'>
		{{Config::get('app.name');}} Membership Rules
	</div>
	All features of {{Config::get('app.name');}} are free to use. There is no charge for becoming a member, offering books to lend, or borrowing books. <br/>
	If the owner charges for lending a book, that is a matter between the borrower and the owner.<br/>
	<br/>
	<b>Member's Responsibility</b><br/>
	<br/>
	To give correct information about himself / herself as pertains to his / her physical self. Virtual identities, fictitious data is not allowed.<br/>
	<br/>
	When adding books you are willing to lend, member will enter data about physical books that he / she indeed owns. Ebooks and fictitious data is not allowed.<br/>
	<br/>
	<b/>{{Config::get('app.name');}}'s Responsibility</b><br/>
	<br/>
	To facilitate managing your book collection and book lending records.<br/>
	<br/>
	To enable you to know of books that are available for borrowing and facilitate communication between book owner and borrower.<br/>
	<br/>
	{{Config::get('app.name');}} is not responsible for physically delivering books to members. The owner and borrower must arrange this within themselves as is mutually convenient for them. ({{HTML::link(URL::route('how-it-works'), 'Read more about how '.Config::get('app.name').' works',
	array('style' => 'font-weight:normal'))}})
	<br/>
	<br/>
	{{Config::get('app.name');}} is not responsible for the loss of books, or if books get mutilated. 
	({{HTML::link(URL::route('faq'), 'Read more about these concerns',
	array('style' => 'font-weight:normal'))}})
	<br/>
	<br/>
	{{Config::get('app.name');}} will not give your email address, phone number, address to other members. However, you can share this information with other members if you wish, to facilitate the process of meeting and lending / returning books.<br/>
	<br/>
	If there is substantial delay in a book being returned, {{Config::get('app.name');}} may share the borrower's contact details with the owner, if the owner requests.<br/>
	<br/>
	{{Config::get('app.name');}} will not share your information with entities outside {{Config::get('app.name');}}, unless required by some governing body, by law.<br/>
	<br/>
	Becoming a member of {{Config::get('app.name');}} implies that you agree with these rules.

</div>

@stop

