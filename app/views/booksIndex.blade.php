
@extends('templates.base')

<?php
	$pendingReqURL = URL::to('pendingRequests');
	$returnForm = URL::to('returnForm');
	$tMsg = ["",""];
	if (Session::has('TransactionMessage'))
	{
		$tMsg = Session::get('TransactionMessage');
		if (($tMsg[0] == 'LendBook') || ($tMsg[0] == 'ReturnBook'))
		{
			Session::forget('TransactionMessage');	
		}
	}
?>

@section('content')
@if ($tMsg[1]!="")
	<p align='center'><span style="border:2px solid blue;padding:4px;background-color:LemonChiffon">{{{$tMsg[1]}}}</span></p>
@endif
<ul>
@foreach($books as $book)
	{{-- var_dump($book) --}}
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
		@if (($category == 'mine') || ($category == 'borrowed'))
			<br/>
			@foreach($book->Copies as $copy)
				{{{$copy->StatusTxt()}}} 
				@if ($category == 'mine')
					@if ($copy->StatusTxt() == 'Available')
						<?php $onclick = "showDivBookCopy('".$copy->ID."','".$pendingReqURL."')"; ?>
						{{ HTML::link('#','Lend', ['onclick'=>$onclick]); }}
						{{"<div id='showDiv2".$copy->ID."' style='display:none'></div>"}}
					@endif
					@if ($copy->StatusTxt() == 'Lent Out')
						<?php $onclick = "showDivBookCopy('".$copy->ID."','".$returnForm."')"; ?>
						{{ HTML::link('#','Accept Return', ['onclick'=>$onclick]); }}
						{{"<div id='showDiv2".$copy->ID."' style='display:none'></div>"}}
					@endif
				@endif
			@endforeach
			<br/><br/>
		@endif	
@endforeach
</ul>
@stop