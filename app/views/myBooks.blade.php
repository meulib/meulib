
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
			Session::forget('TransactionMessage');
	}
?>

@section('content')

@if (!$loggedIn)
	Join / Login to request books, to add your own books to lend.
@endif

@if ($tMsg[1]!="")
	<p align='center'>
		<span style="border:1px solid blue;padding:4px;background-color:LemonChiffon">
			{{{$tMsg[1][1] }}}
			@if ($tMsg[1][0] && ($tMsg[0] == 'AddBook'))
				<a href="#AddBooks">Add More Books</a>
			@endif
		</span>
	</p>
@endif
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
			<br/>
			@foreach($book->Copies as $copy)
				{{{$copy->StatusTxt()}}} 
				@if ($copy->StatusTxt() == 'Available')
					<?php $onclick = "showLendForm('".$copy->ID."','".$pendingReqURL."')"; ?>
					{{ HTML::link('#','Lend', ['onclick'=>$onclick]); }}
					<br/>
					{{"<div id='showDiv2".$copy->ID."' style='display:none; border:2px grey solid;padding: 5px;'></div>"}}
				@endif
				@if ($copy->StatusTxt() == 'Lent Out')
					<?php $onclick = "showLendForm('".$copy->ID."','".$returnForm."')"; ?>
					{{ HTML::link('#','Accept Return', ['onclick'=>$onclick]); }}
					{{"<div id='showDiv2".$copy->ID."' style='display:none'></div>"}}
				@endif
			@endforeach
			<br/><br/>
	@endforeach
@endif
</ul>

@if (Session::has('loggedInUser'))
	@include('templates.addBooks')
@endif

@stop