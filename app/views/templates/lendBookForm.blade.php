<?php
	$requests = count($requestTransactions);
?>
<br/>
@if ($requests == 0)
	No Pending Requests
@else
	<b>Pending Requests</b>
{{ Form::open(array('action' => 'TransactionController@lend')) }}
	{{ Form::hidden('bookCopyID',$bookCopyID) }}
	@foreach ($requestTransactions as $request)
		{{ Form::radio('lendToID', $request->Borrower) }}
		{{ Form::label('', $request->BorrowerUser->FullName) }}
		<br/>
	@endforeach
	<br/>
	{{ Form::submit('Lend'); }}
{{ Form::close() }}

@endif