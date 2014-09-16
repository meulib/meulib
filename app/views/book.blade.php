
@extends('templates.base')

<?php
	$loggedIn = false;
	if (Session::has('loggedInUser'))
		$loggedIn = true;
	$title = $book->Title;
	if ($book->SubTitle)
		$title .= ': '.$book->SubTitle;
	$tMsg = ["",""];
	if (Session::has('TransactionMessage'))
	{
		$tMsg = Session::get('TransactionMessage');
		if ($tMsg[0] == 'RequestBook')
		{
			Session::forget('TransactionMessage');	
		}
	}
?>

@section('content')
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
	Book Copies ({{{count($copies)}}})
	<br/><br/>
	<b>Lenders</b><br/><br/>
	@foreach($copies as $bCopy)
		@if ($loggedIn)
			{{{$bCopy->Owner->FullName.': '}}}
		@endif
		{{{$bCopy->Owner->City.', '.$bCopy->Owner->Locality}}}<br/>
		@if ($loggedIn)
			<?php $onclick = "showDiv('requestBook".$bCopy->ID."')"; ?>
			{{ HTML::link('#','Request Book', ['onclick'=>$onclick]); }}
			@include('templates.requestBookForm')
		@endif
	@endforeach
@stop

