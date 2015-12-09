
@extends('templates.base')

<?php
	$loggedIn = false;
	if (Session::has('loggedInUser'))
		$loggedIn = true;

	$bookCount = count($books['data']);
?>

@section('title', 'Search Books: ')

@section('content')

<!-- --- BOOK LISTING --- -->

@if ($bookCount > 0)
	<br/>
		@foreach($books['data'] as $book)
			<div class="bookMat">
				<a href={{  URL::route('single-book', array($book->ID))}}>
				@if (strlen($book->CoverFilename)>0)
				{{ HTML::image('images/book-covers/'.$book->CoverFilename, 'a picture', array('height' => '150')) }}<br/>
				@endif
				{{{ $book->Title }}}
				@if ($book->SubTitle)
					<div class="bookMatSubTitle">
					{{-- @if (strlen($book->SubTitle)>30)
						{{{ substr($book->SubTitle,0,30).'...' }}}
					@else --}}
						{{{ $book->SubTitle }}}
					{{-- @endif --}}
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
	<br/>
@else
		No books found in for Search Term: <b>xxx</b>
@endif

@if (Session::has('loggedInUser'))
	@include('templates.addBooks')
@endif

@stop