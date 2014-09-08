<br/>

Please confirm. Book returned by<br/>
	{{ Form::open(array('action' => 'TransactionController@acceptReturn')) }}
		{{ Form::hidden('bookCopyID',$bookCopyID) }}
		{{ Form::checkbox('returnFromID', $lentRecord->Borrower) }}
		{{ Form::label('', $lentRecord->BorrowerUser->FullName) }}
		<br/>
		{{ Form::submit('Accept Return'); }}
	{{ Form::close() }}
