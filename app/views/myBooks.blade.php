
@extends('templates.base')

<?php
	$loggedIn = false;
	if (Session::has('loggedInUser'))
	{
		$loggedIn = true;
		$user = Session::get('loggedInUser');
	}
	$pendingReqURL = URL::to('pending-requests');
	$returnForm = URL::to('return-form');
	$tMsg = ["",""];
	if (Session::has('TransactionMessage'))
	{
		$tMsg = Session::get('TransactionMessage');
			Session::forget('TransactionMessage');
	}
?>

@section('content')

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
<span class="pageTitle">{{$user->FullName."'s Very Own Collection"}}</span>
@if (count($books)>0)
	{{ $books->links() }}
	<ul>
	@foreach($books as $book)
		<li>
			<a href={{  URL::action('BookController@showSingle', array($book->BookID))}}>
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
			<!--br/-->
			(
			@if ($book->StatusTxt() == 'Available')
				<?php $onclick = "showLendForm('".$book->BookCopyID."','".$pendingReqURL."')"; ?>
				{{ HTML::link('#','Lend', ['onclick'=>$onclick]); }}
				{{--"<div id='showDiv2".$copy->ID."' style='display:none; border:2px grey solid;padding: 5px;'></div>"--}}
				{{"<div id='showDiv2".$book->BookCopyID."' style='display:none;' class='formDiv'></div>"}}
			@endif
			@if ($book->StatusTxt() == 'Lent Out')
				{{{$book->StatusTxt()}}}
				{{ 'on '.$book->niceLentOutDt().'. '.$book->daysAgoLentOut().' days ago'}}
				<?php $onclick = "showLendForm('".$book->BookCopyID."','".$returnForm."')"; ?>
				{{ HTML::link('#','Accept Return', ['onclick'=>$onclick]); }}
				{{"<div id='showDiv2".$book->BookCopyID."' style='display:none' class='formDiv'></div>"}}
			@endif
			)
	@endforeach
	</ul>
	{{ $books->links() }}
@else
	There are no books yet in your very own {{Config::get('app.name');}} collection.<br/><br/>
@endif


@if (Session::has('loggedInUser'))
	@include('templates.addBooks')
@endif

@stop