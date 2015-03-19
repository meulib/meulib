<div class="formDiv">
	<a name="AddBooks"></a>
	<span class="formTitle">
		@if (isset($addMoreBooks) && ($addMoreBooks))
			Add More Books
		@else
			Add Books That You Are Willing To Lend
		@endif
		</span><br/>
	<br/>
	{{ Form::open(array('action' => 'BookController@addBook')) }}
	Title<br/>
	{{ Form::text('Title', '', ['required','size'=>40,'maxlength'=>100]) }}<br/>
	Sub Title<br/>
	{{ Form::text('SubTitle', '', ['size'=>40,'maxlength'=>100]) }}<br/>
	Author <span style="font-size:90%">(or editor or series name)</span><br/>
	{{ Form::text('Author1', '', ['required','size'=>40,'maxlength'=>100]) }}<br/>
	Any other authors?<br/>
	{{ Form::text('Author2', '', ['size'=>40,'maxlength'=>100]) }}<br/>
	Language<br/>
	{{ Form::text('Language1', 'English', ['required','maxlength'=>50]) }}<br/>
	Any other language? <span style="font-size:90%">(for multi-lingual books)</span><br/>
	{{ Form::text('Language2', '', ['maxlength'=>50]) }}<br/>
	{{ Form::checkbox('ForGiveAway', 1, false, ['style'=>'width:1em;height:1em;']) }} For Give Away? <span style="font-size:90%">(check if you no longer want to own this book and want to give it away)</span><br/>
	{{ Form::submit('Yes, Add', array('class' => 'richButton')); }}
	{{ Form::close() }}
</div>