
@extends('templates.base')

<?php
	$loggedIn = false;
	if (Session::has('loggedInUser'))
		$loggedIn = true;
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
<ul>
@if ($bookCopies)
	@foreach($bookCopies as $bookCopy)
		<li>
			<a href={{  URL::action('BookController@showSingle', array($bookCopy->Book->ID))}}>
			{{{ $bookCopy->Book->Title }}}
			@if ($bookCopy->Book->SubTitle)
				{{{ ": ".$bookCopy->Book->SubTitle }}}
			@endif
			</a>
			@if ($bookCopy->Book->Author1)
				{{{ "&nbsp;by ".$bookCopy->Book->Author1 }}}
			@endif
			@if ($bookCopy->Book->Author2)
				{{{ ", ".$bookCopy->Book->Author2 }}}
			@endif
			<br/>
			{{{$bookCopy->StatusTxt()}}} 
			{{ 'on '.$bookCopy->niceLentOutDt().'. '.$bookCopy->daysAgoLentOut().' days ago'}}
			<br/><br/>			
	@endforeach
@else
	You do not have any borrowed books right now.
@endif
</ul>

@stop