
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
<div class='contentDiv' style='margin:0;display:block;'>

@if (!$loggedIn)
	<form action={{URL::to('/signup-or-login')}}>
		{{ Form::submit('Become a Member', 
			array('class' => 'richButton',
			'name'=>'btnMember')); }}
		{{ Form::submit('Login', 
			array('class' => 'normalButton',
			'name'=>'btnLogin')); }} for more details about the owner(s) and to request this book.
	</form>	
	<br/><br/>
@endif

@if ($tMsg[1]!="")
	<p align='center'><span style="border:2px solid blue;padding:4px;background-color:LemonChiffon">{{{$tMsg[1]}}}</span></p>
@endif

	@if (strlen($book->CoverFilename)>0)
		{{ HTML::image('images/book-covers/'.$book->CoverFilename, '', array('height' => '200','style'=>'float: left; margin-right: 15px;')) }}
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
		@if ($loggedIn)
			@if ($bCopy->Owner->UserID != $loggedInUser->UserID)
				Book Status: {{ $bCopy->StatusTxt() }}
				<br/>
				<?php $onclick = "showDiv('requestBook".$bCopy->ID."')"; ?>
				{{ HTML::link('#','Request Book', ['onclick'=>$onclick]); }}
				@include('templates.requestBookForm')
			@else
				This is you!<br/>
				Book Status: {{ $bCopy->StatusTxt() }}
				@if ($bCopy->StatusTxt() == 'Lent Out')
					{{ 'on '.$bCopy->niceLentOutDt().'. '.$bCopy->daysAgoLentOut().' days ago'}}
				@endif
				<br/>
				<?php $onclick = "showPostDiv('".$bCopy->ID."','".$deleteFormURL."')"; ?>
				<span class="aTreadCarefully">{{ HTML::link('#','Delete This Copy', ['onclick'=>$onclick]) }}</span>
				{{"<div id='postDiv".$bCopy->ID."' style='display:none;' class='carefulDiv'></div>"}}
			@endif
		@endif
		<br/><br/>
	@endforeach
</div>
@stop

