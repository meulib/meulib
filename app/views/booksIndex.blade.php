
@extends('templates.base')

<?php
	$loggedIn = false;
	if (Session::has('loggedInUser'))
		$loggedIn = true;
	$pendingReqURL = URL::to('pendingRequests');
	$returnForm = URL::to('returnForm');
	$tMsg = ["",""];
	if (Session::has('TransactionMessage'))
	{
		$tMsg = Session::get('TransactionMessage');
		//if (($tMsg[0] == 'LendBook') || ($tMsg[0] == 'ReturnBook'))
		//{
			Session::forget('TransactionMessage');	
		//}
	}
	if ($currentLanguage == 'all')
		$currentLanguageLinkValue = 'all';
	else
		$currentLanguageLinkValue = $currentLanguage->LanguageEnglish;

	if ($currentLocation == 'all')
		$currentLocationLinkValue = 'all';
	else
		$currentLocationLinkValue = $currentLocation->Location;

	if ($currentCategory == 'all')
		$currentCategoryLinkValue = 'all';
	else
		$currentCategoryLinkValue = $currentCategory->Category;

	$bookCount = $books->count();
?>

@section('content')

<!-- --- BROWSE FILTER SECTION --- -->
<div class="filterSection">

<!-- --- LOCATION --- -->
@if ($locations)
Location: 
	@if ($currentLocation != 'all')
		<a href={{ URL::action('BookController@showAll', array('all',$currentLanguageLinkValue,$currentCategoryLinkValue))}}>
			all
		</a>
	@else
		<b>all</b>
	@endif
	@foreach($locations as $location)
			| 
		@if (($currentLocation == 'all') || ($currentLocation->ID != $location->ID))
			<a href={{ URL::action('BookController@showAll', array($location->Location,$currentLanguageLinkValue,$currentCategoryLinkValue))}}>
				{{{ $location->Location}}}
			</a>
		@else
			<b>{{{ $location->Location }}}</b>
		@endif
	@endforeach
@endif
<!--<br/>
Showing: <b>{{{ $currentLocation }}}</b><br/>-->

<!-- --- LANGUAGE --- -->
@if ($languages)
<br/>
Language: 
	@if ($currentLanguage != 'all')
		<a href={{ URL::action('BookController@showAll', array($currentLocationLinkValue,'all',$currentCategoryLinkValue))}}>
			all
		</a>
	@else
		<b>all</b>
	@endif
	@foreach($languages as $language)
			| 
		@if (($currentLanguage == 'all') || ($currentLanguage->ID != $language->ID))
			<a href={{ URL::action('BookController@showAll', array($currentLocationLinkValue,$language->LanguageEnglish,$currentCategoryLinkValue))}}>
				{{{ $language->LanguageEnglish}}}
			</a>
		@else
			<b>{{{ $language->LanguageEnglish }}}</b>
		@endif
	@endforeach
@endif

<!-- --- CATEGORY --- -->
<br/>
@if ($categories)
Category: 
	@if ($currentCategory != 'all')
		<a href={{ URL::action('BookController@showAll', array($currentLocationLinkValue,$currentLanguageLinkValue,'all'))}}>
			all
		</a>
	@else
		<b>all</b>
	@endif
	@foreach($categories as $category)
			| 
		@if (($currentCategory == 'all') || ($currentCategory->ID != $category->ID))
			<a href={{ URL::action('BookController@showAll', array($currentLocationLinkValue,$currentLanguageLinkValue,$category->Category))}}>
				{{{ $category->Category}}}
			</a>
		@else
			<b>{{{ $category->Category }}}</b>
		@endif
	@endforeach
@endif
</div>
<!-- --- END BROWSE FILTER SECTION --- -->

<!--
@if (!$loggedIn)
	<form action={{URL::to('/signup-or-login')}}>
		{{ Form::submit('Become a Member', 
			array('class' => 'richButton',
			'name'=>'btnMember')); }}
		{{ Form::submit('Login', 
			array('class' => 'normalButton',
			'name'=>'btnLogin')); }} to request books to borrow, to add your own books to lend.
	</form>	
@endif
-->

@if ($tMsg[1]!="")
	<p align='center'>
		<span style="border:2px solid blue;padding:4px;background-color:LemonChiffon">
			{{{$tMsg[1][1] }}}
		</span>
	</p>
@endif

<!-- --- BOOK LISTING --- -->

@if ($bookCount > 0)
	{{ $books->links() }}
	<br/>
		@foreach($books as $book)
			@if (strlen($book->CoverFilename)>0)
				<div style="display:inline-block;padding:2px;margin:2px;width:200px;text-align:center;vertical-align:top;background-color: #f0f9f0;">
					<a href={{  URL::action('BookController@showSingle', array($book->ID))}}>
					{{ HTML::image('images/book-covers/'.$book->CoverFilename, 'a picture', array('height' => '150')) }}<br/>
					{{{ $book->Title }}}
					</a>
					@if ($book->SubTitle)
						<div style="font-size:70%;">
						@if (strlen($book->SubTitle)>30)
							{{{ substr($book->SubTitle,0,30).'...' }}}
						@else
							{{{ $book->SubTitle }}}
						@endif
						</div>
					@endif
					@if ($book->Author1)
						<div style="font-size:90%">
						{{{ $book->Author1 }}}
						@if ($book->Author2)
							{{{ ", ".$book->Author2 }}}
						@endif
						</div>
					@endif
				</div>
			@else
				<div style="display:inline-block;padding:2px;margin:2px;width:200px;text-align:center;vertical-align:top;background-color: #f0f9f0;">
					<a href={{  URL::action('BookController@showSingle', array($book->ID))}}>
					{{{ $book->Title }}}
					</a>
					@if ($book->SubTitle)
						<div style="font-size:70%;">
						{{{ $book->SubTitle }}}
						</div>
					@endif
					@if ($book->Author1)
						<div style="font-size:90%">
						{{{ $book->Author1 }}}
						@if ($book->Author2)
							{{{ ", ".$book->Author2 }}}
						@endif
						</div>
					@endif
				</div>
			@endif
			<!-- 
			{{{ $book->Title }}}
			@if ($book->SubTitle)
				{{{ ": ".$book->SubTitle }}}
			@endif
			</a>
			@if ($book->Author1)
				{{{ "&nbsp;by ".$book->Author1 }}}
			@endif
			@if ($book->Author2)
				{{{ ", ".$book->Author2 }}}
			@endif -->
		@endforeach
	<br/>
	{{ $books->links() }}
@else
		No books found in Location <b>{{$currentLocationLinkValue}}</b> in Language <b>{{$currentLanguageLinkValue}}</b> of Category <b>{{$currentCategoryLinkValue}}</b>
@endif

@if (Session::has('loggedInUser'))
	@include('templates.addBooks')
@endif

@stop