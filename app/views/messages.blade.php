
@extends('templates.base')

<?php
	$tranID = 0;
	$msgCount = 0;
	if (isset($msgs))
	{
		$tranID = $msgs[0]->TransactionID;
		$msgCount = count($msgs);
	}		
	$userID = Session::get('loggedInUser')->UserID;
?>

@section('content')
	<ul>
	{{-- var_dump($msgCount) --}}
	@foreach ($msgTransactions as $tran)
		<li>
		@if ($tranID != $tran->ID)
			<a href={{ URL::action('TransactionController@messages', array($tran->ID))}}>
		@endif
		{{{ $tran->Book->Title }}}
		@if ($tran->Book->SubTitle)
			{{{ ": ".$tran->Book->SubTitle }}}
		@endif
		 --- 
		{{{$tran->LenderUser->FullName}}}
		 --- 
		{{{$tran->BorrowerUser->FullName}}}
		 --- 
		{{{Transaction::tStatusByVal($tran->Status)}}}
		@if ($tranID != $tran->ID)
			</a>
			<br/>
			<br/>
		@else
			<br/>
			<?php $i = 0; ?>
			<table border=1 cellspacing=0 cellpadding=5>
				<tr><td><b>From</b></td><td><b>To</b></td>
				<td><b>Message</b></td><td></td></tr>
				@foreach ($msgs as $msg)
					<?php $i++; ?>
					<tr>
					@if ($msg->FromTo == MESSAGE_FROM)
						<td>@if ($msg->UserID == $userID) Me @else {{{$msg->User->FullName}}} @endif</td>
						<td>@if ($msg->OtherUserID == $userID) Me @else {{{$msg->OtherUser->FullName}}} @endif</td>
						<td style='background-color:#99FFFF'>
					@else
						<td>@if ($msg->OtherUserID == $userID) Me @else {{{$msg->OtherUser->FullName}}} @endif</td>
						<td>@if ($msg->UserID == $userID) Me @else {{{$msg->User->FullName}}} @endif</td>
						<td style='background-color:#CCFF99'>
					@endif
					{{{$msg->Message}}}
					</td><td>
					@if ($i == $msgCount)
						<?php $onclick = "showDiv('replyMessage".$tran->ID."')"; ?>
						{{ HTML::link('#','Reply', ['onclick'=>$onclick]); }}
					@endif
					</td></tr>
				@endforeach
			</table>
			@include('templates.replyMsgForm')
			<br/>
		@endif
	@endforeach
	</ul>
@stop

