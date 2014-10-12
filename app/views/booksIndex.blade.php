
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
<hr/>
<!-- --- LIBRARY LOCATIONS LISTING --- -->
Locations: 
@if ($locations)
	@foreach($locations as $location)
		<a href={{ URL::action('BookController@showAll', array($location->ID))}}>
			{{{ $location->Location . ', ' . $location->Country}}}
		</a>
		 | 
	@endforeach
@endif
<br/>
Showing: {{{ $currentLocation }}}
<hr/>
<!-- --- BOOK LISTING --- -->
<ul>
@if ($books)
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
@endif
</ul>

@if (Session::has('loggedInUser'))
	<hr/>
	<a name="AddBooks"></a>
	ADD BOOKS THAT YOU ARE WILLING TO LEND<br/>
	<br/>
	{{ Form::open(array('action' => 'BookController@addBook')) }}
	Title<br/>
	{{ Form::text('Title', '', ['required','size'=>40,'maxlength'=>100]) }}<br/>
	Sub Title<br/>
	{{ Form::text('SubTitle', '', ['size'=>40,'maxlength'=>100]) }}<br/>
	Author (or editor or series name)<br/>
	{{ Form::text('Author1', '', ['required','size'=>40,'maxlength'=>100]) }}<br/>
	Any other authors?<br/>
	{{ Form::text('Author2', '', ['size'=>40,'maxlength'=>100]) }}<br/>
	Language<br/>
	{{ Form::text('Language1', 'English', ['required','maxlength'=>50]) }}<br/>
	Any other language? (for multi-lingual books)<br/>
	{{ Form::text('Language2', '', ['maxlength'=>50]) }}<br/>
	{{ Form::submit('Add'); }}
	{{ Form::close() }}
@endif

@stop