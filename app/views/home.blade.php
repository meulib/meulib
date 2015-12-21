@extends('templates.base')

<?php
	$msg = false;
	if (Session::has('LoginMessage'))
	{
		$msg = Session::get('LoginMessage');
		Session::forget('LoginMessage');
		$fromURL = $msg['fromURL'];
	}

	$appName = Config::get('app.name');
	$whatsNew = Config::get('app.whatsnew');
	$randomPromo = rand(1,9);
	// $randomPromo = 7;
	$booksList = array_slice($books['data'], 0, 6);
?>

@section('content')
	@include('templates.searchBox')
	<div class="contentDiv" style="width:100%">
	<div class="homeCenterDiv" id="homeLoginSignupButtons">
		<form action={{URL::to('/signup-or-login')}}>
			{{ Form::submit('Login', 
				array('class' => 'normalButton',
				'name'=>'btnLogin')); }}
			{{ Form::submit('Become a Member', 
				array('class' => 'richButton',
				'name'=>'btnMember')); }}
			<br/>
			<b>{{HTML::link(URL::to('/how-it-works'), 'How It Works')}}</b>
		</form>
	</div>
	<div class="homeCenterDiv" style="font-size:120%;margin-bottom:5px;font-weight:bold">
		@if ($randomPromo == 1)
			<a href={{ URL::to('/how-it-works-owner') }}>
			{{ HTML::image('images/howitworks/o1.png','',array('width'=>287, 'height'=>246,
						'style'=>'max-width:100%')) }}
			<br/>
			See how {{Config::get('app.name')}} works for the book owner</a>
		@endif
		@if ($randomPromo == 2)
			<div class="pageTitle" style="color:green">Vision</div>
			<span style="font-weight:normal">A self-sustained unlimited public library.<br/>
						Unlimited in collection, unlimited in locations.<br/>
						Made possible by Me and You.<br/></span>
						<b>Because we share!</b>
			{{-- HTML::image('images/promo/vision.png','',array('style'=>'max-width:100%')) --}}
		@endif
		@if ($randomPromo == 3)
			<a href={{ URL::to('/how-it-works-borrower') }}>
			{{ HTML::image('images/howitworks/b1.png','',array('width'=>287, 'height'=>246,
					'style'=>'max-width:100%')) }}
			<br/>
			See how {{Config::get('app.name')}} works for the borrower</a>
		@endif
		@if ($randomPromo == 4)
			{{ HTML::image('images/promo/directLending.png','',array('style'=>'max-width:100%')) }}
		@endif
		@if ($randomPromo == 5)
			Inundated with books? Give away the ones you no longer want.<br/>
			{{ HTML::image('images/promo/pikuFB.jpg','',array('width'=>500,'style'=>'max-width:100%')) }}<br/>
			<span style="font-size:80%">Image taken from the movie "Piku".</span>
		@endif
		@if ($randomPromo == 6)
			What a book means to us. Word-o-graphic <a href="https://www.facebook.com/groups/meulib" target="_blank">co-created by 
			MeULib members and others</a>.<br/>
			{{ HTML::image('images/promo/book-word-vis.png','',array('width'=>500,'style'=>'max-width:100%')) }}
		@endif
		@if ($randomPromo == 7)
			<a href="http://meulib.com/blog/books-depreciate/">
			<img src="http://meulib.com/blog/wp-content/uploads/2015/08/cars-books-veg2-825x510.png" width=500 style="max-width:100%" /><br/>
			<b>Something is happening to your books sitting idle ...</b>
			</a>
		@endif
		@if ($randomPromo == 8)
			<a href="http://meulib.com/blog/books-on-our-journey-post/">
				<b>Here I was, reading the gripping Alistair Maclean novel ...</b><br/>
			<img src="http://meulib.com/blog/wp-content/uploads/2015/09/st8-825x510.jpg" width=500 style="max-width:100%" /><br/>
			
			</a>
		@endif
		@if ($randomPromo == 9)
			<a href="http://meulib.com/blog/elated-to-be-a-woman/">
				<b>Elated To Be A Woman ... Article on the book Women's Bodies Women's Wisdom</b><br/>
			<img src="http://meulib.com/blog/wp-content/uploads/2015/10/Happy-Day-1200-900-825x510.jpg" width=500 style="max-width:100%" /><br/>
			
			</a>
		@endif
	</div>
	<span style="font-size:120%;font-weight:bold">Recently Added Books | {{ HTML::link(URL::route('browse'), 'More ... browse by location, language, category') }}</span>
	<br/>
	@foreach($booksList as $book)
		<div class="bookMat">
			<a href={{  URL::route('single-book', array($book->ID))}}>
			@if (strlen($book->CoverFilename)>0)
			{{ HTML::image('images/book-covers/'.$book->CoverFilename, 'a picture', array('height' => '150')) }}<br/>
			@endif
			{{{ $book->Title }}}
			@if ($book->SubTitle)
				<div class="bookMatSubTitle">
					{{{ $book->SubTitle }}}
				</div>
			@endif
			</a>
			@if ($book->Author1)
				<div class="bookMatAuthor">
				{{{ $book->Author1 }}}
				@if ($book->Author2)
					{{{ ", ".$book->Author2 }}}
				@endif
				</div>
			@endif
		</div>
	@endforeach
	<br/>
	<b>{{ HTML::link(URL::route('browse'), 'More ... browse by location, language, category') }}</b>
	</div>
@stop
