
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
?>

@section('content')

<!-- --- BROWSE FILTER SECTION --- -->
<!-- LOCATION -->
<div class="filterSection">
<?php $firstElement = true; ?>
Locations: 
@if ($locations)
	@foreach($locations as $location)
		@if (!$firstElement)
			| 
		@else
			<?php $firstElement = false; ?>
		@endif
		<a href={{ URL::action('BookController@showAll', array($location->ID,$currentLanguageID))}}>
			{{{ $location->Location . ', ' . $location->Country}}}
		</a>
	@endforeach
@endif
<br/>
Showing: <b>{{{ $currentLocation }}}</b><br/>
<!-- LANGUAGE -->
<br/>
<?php $firstElement = true; ?>
Languages:
@if ($languages)
	@foreach($languages as $language)
		@if (!$firstElement)
			| 
		@else
			<?php $firstElement = false; ?>
		@endif
		<a href={{ URL::action('BookController@showAll', array($currentLocationID,$language->ID))}}>
			{{{ $language->LanguageEnglish }}}
		</a>
	@endforeach
@endif
<br/>
Showing: <b>{{{ $currentLanguage }}}</b><br/>
</div>
<!-- --- END BROWSE FILTER SECTION --- -->

@if (!$loggedIn)
	Join / Login to request books, to add your own books to lend.
@endif

@if ($tMsg[1]!="")
	<p align='center'>
		<span style="border:2px solid blue;padding:4px;background-color:LemonChiffon">
			{{{$tMsg[1][1] }}}
			@if ($tMsg[1][0] && ($tMsg[0] == 'AddBook'))
				<a href="#AddBooks">Add More Books</a>
			@endif
		</span>
	</p>
@endif

<!-- --- BOOK LISTING --- -->

<ul>
@if ($books)
{{ $books->links() }}
<br/>
	@foreach($books as $book)
		<li>
			<a href={{  URL::action('BookController@showSingle', array($book->ID))}}>
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
			@endif
		</li>	
	@endforeach
<br/>
{{ $books->links() }}
@endif
</ul>

@if (Session::has('loggedInUser'))
	@include('templates.addBooks')
@endif

@stop