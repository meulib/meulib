
@extends('templates.base')

<?php
	$loggedIn = false;
	if (Session::has('loggedInUser'))
	{
		$loggedIn = true;
		$loggedInUser = Session::get('loggedInUser');
	}
	$title = $book->FullTitle();
	// if ($book->SubTitle)
	// 	$title .= ': '.$book->SubTitle;
	$tMsg = ["",""];
	if (Session::has('TransactionMessage'))
	{
		$tMsg = Session::get('TransactionMessage');
		if ($tMsg[0] == 'RequestBook')
		{
			Session::forget('TransactionMessage');	
		}
	}
	$deleteFormURL = URL::to('delete-book-confirmation');
?>

@section('content')

@if (!$loggedIn)
	Join / Login for more details about the owner(s) and to request this book.
	<br/><br/>
@endif

@if ($tMsg[1]!="")
	<p align='center'><span style="border:2px solid blue;padding:4px;background-color:LemonChiffon">{{{$tMsg[1]}}}</span></p>
@endif
	<b>{{{$title}}}</b><br/>
	@if ($book->Author1)
		{{{ $book->Author1 }}}
		@if ($book->Author2)
			{{{ ", ".$book->Author2 }}}
			 (Authors)
		@else
			  (Author)
		@endif		
	@endif
	<br/><br/>
	@if (count($bookCategories)>0)
		Categorised as: 
		@foreach($bookCategories as $bCategory)
			{{$bCategory->Category}}
		@endforeach
		<br/><br/>
	@endif
	Book Copies ({{{count($copies)}}})
	<br/><br/>
	<b>Owners</b><br/>
	@foreach($copies as $bCopy)
		@if ($loggedIn)
			{{{$bCopy->Owner->FullName.': '}}}
		@endif
		in 
		{{{$bCopy->Owner->City.', '.$bCopy->Owner->Locality}}}<br/>
		Book Status: {{ $bCopy->StatusTxt() }}<br/>
		@if ($loggedIn)
			@if ($bCopy->Owner->UserID != $loggedInUser->UserID)
				<?php $onclick = "showDiv('requestBook".$bCopy->ID."')"; ?>
				{{ HTML::link('#','Request Book', ['onclick'=>$onclick]); }}
				@include('templates.requestBookForm')
			@else
				This is you!<br/>
				<?php $onclick = "showPostDiv('".$bCopy->ID."','".$deleteFormURL."')"; ?>
				<span class="aTreadCarefully">{{ HTML::link('#','Delete This Copy', ['onclick'=>$onclick]) }}</span>
				{{"<div id='postDiv".$bCopy->ID."' style='display:none;' class='carefulDiv'></div>"}}
			@endif
		@endif
		<br/><br/>
	@endforeach
@stop

