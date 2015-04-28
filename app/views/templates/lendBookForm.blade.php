<?php
	$requests = count($requestTransactions);
	if ($giveAway)
	{
		$actionText = 'Give Away';
		$formAction = 'TransactionController@giveAway';
	}
	else
	{
		$actionText = 'Lend';
		$formAction = 'TransactionController@lend';
	}
	$formName = 'lendForm';
		
?>
<span id='lendFormMsg{{$bookCopyID}}' style="padding:4px;background-color:LemonChiffon;display:none;"></span>
<br/>

{{ Form::open(array('action' => $formAction,
					'name' => $formName.$bookCopyID)) }}
	{{ Form::hidden('bookCopyID',$bookCopyID) }}
	{{ Form::hidden('giveAway'.$bookCopyID,$giveAway) }}
	@if ($requests > 0)
		{{ Form::hidden('existsRequests'.$bookCopyID,true) }}
		<b>{{$actionText.' to'}}</b><br/>
			someone who has requested<br/>
		@foreach ($requestTransactions as $request)
			{{ Form::radio('userToID'.$bookCopyID, $request->Borrower, false, 
				array('onclick' => 
						"hideLendOtherForm('lendOtherForm".$bookCopyID."');")) }}
			{{ Form::label('', $request->BorrowerUser->FullName) }}
			<br/>
		@endforeach
		<br/>
		{{ Form::radio('userToID'.$bookCopyID, -1, false, 
				array('onclick' => 
						"showLendOtherForm('lendOtherForm".$bookCopyID."');")) }}
		{{ Form::label('', 'someone else') }}
		{{"<div id='lendOtherForm".$bookCopyID."' style='display:none;'>"}}
			<table border=0><tr>
			<td>{{ Form::label('l_name', 'Name'); }}: </td>
			<td>{{ Form::text('bName'.$bookCopyID, '', []); }}</td></tr><tr>
			<td>{{ Form::label('l_email', 'Email'); }}: </td>
			<td>{{ Form::email('bEmail'.$bookCopyID, '', []);}}</td></tr><tr>
			<td>{{ Form::label('l_phone', 'Phone'); }}: <br/>
			(digits only)</td>
			<td>{{ Form::text('bPhone'.$bookCopyID, '', []); }}
			</td></tr></table>
		</div>
	@endif
	@if ($requests == 0)
		{{ Form::hidden('existsRequests'.$bookCopyID,false) }}
		<b>{{$actionText}} To</b></br>
		<table border=0><tr>
		<td>{{ Form::label('l_name', 'Name'); }}: </td>
		<td>{{ Form::text('bName'.$bookCopyID, '', []); }}</td></tr><tr>
		<td>{{ Form::label('l_email', 'Email'); }}: </td>
		<td>{{ Form::email('bEmail'.$bookCopyID, '', []);}}</td></tr><tr>
		<td>{{ Form::label('l_phone', 'Phone'); }}: <br/>
		(digits only)</td>
		<td>{{ Form::text('bPhone'.$bookCopyID, '', array('pattern' => '/^[0-9]+$/')); }}
		</td></tr></table>
	@endif
	<br/>
	<input class="normalButton" type="button" id="lendBookButton" value='{{$actionText}}' onclick=lendFormSubmit({{$bookCopyID}},'{{{URL::to('lend')}}}')>
{{ Form::close() }}
