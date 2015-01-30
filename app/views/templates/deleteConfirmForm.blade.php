<br/>
@if ($activeTransactions >0)
<b>There are transactions currently active for this book copy.</b><br/>
@endif
Do you <i>really</i> want to delete this book from {{Config::get('app.name')}}?<br/>
	{{ Form::open(array('action' => 'BookController@deleteBookCopy')) }}
		{{ Form::hidden('bookCopyID',$bookCopyID) }}
		{{ Form::submit('Yes, Delete', array('class' => 'carefulButton')) }}
		<input class="normalButton" type="button" id="delBookCancelBtn" value="No, Cancel" onclick=hideDiv({{$bookCopyID}})>
	{{ Form::close() }}
