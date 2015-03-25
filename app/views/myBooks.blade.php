
@extends('templates.base')

@section('title', 'My Collection: ')

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
<span class="pageTitle">{{$user->FullName."'s Collection"}}</span>
<p align="center">{{ $user->Locality . ', ' . $user->City . '. ' . 
	$user->State . ', ' . $user->Country }}</p>
@if (count($books)>0)
	{{ $books->links() }}
	<!-- ul -->
	@foreach($books as $bookCopy)
		<!-- li -->
		<div style="margin-left:10px;margin-bottom:10px;">
			<a href={{  URL::action('BookController@showSingle', array($bookCopy->Book->ID))}}>
			@if (strlen($bookCopy->Book->CoverFilename)>0)
				{{ HTML::image('images/book-covers/'.$bookCopy->Book->CoverFilename, '', array('height' => '100','style'=>'float: left; margin-right: 15px;')) }}
			@endif
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
			<!--br/-->
			(
			@if ($bookCopy->StatusTxt() == 'Available')
				<?php $onclick = "showLendForm('".$bookCopy->BookCopyID."','".$pendingReqURL."')"; ?>
				{{ HTML::link('#','Lend', ['onclick'=>$onclick]); }}
				{{--"<div id='showDiv2".$copy->ID."' style='display:none; border:2px grey solid;padding: 5px;'></div>"--}}
				{{"<div id='showDiv2".$bookCopy->BookCopyID."' style='display:none;' class='formDiv'></div>"}}
			@endif
			@if ($bookCopy->StatusTxt() == 'Lent Out')
				{{{$bookCopy->StatusTxt()}}}
				{{ 'on '.$bookCopy->niceLentOutDt().'. '.$bookCopy->daysAgoLentOut().' days ago'}}
				<?php $onclick = "showLendForm('".$bookCopy->BookCopyID."','".$returnForm."')"; ?>
				{{ HTML::link('#','Accept Return', ['onclick'=>$onclick]); }}
				{{"<div id='showDiv2".$bookCopy->BookCopyID."' style='display:none' class='formDiv'></div>"}}
			@endif
			)
			<span style="display: block; clear: both; width: 1px; height: 0.001%; font-size: 0px; line-height: 0px;"/>
		</div>
	@endforeach
	<!-- /ul -->
	{{ $books->links() }}
@else
	There are no books yet in your {{Config::get('app.name');}} collection.<br/><br/>
@endif


@if (Session::has('loggedInUser'))
	@include('templates.addBooks')
@endif

@stop