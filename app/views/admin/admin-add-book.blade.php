
@extends('templates.base')

@section('content')

<div class="contentDiv">
<div class="formDiv">
	<a name="AddBooks"></a>
	<span class="formTitle">
		@if (isset($addMoreBooks) && ($addMoreBooks))
			Add More Books On Behalf Of User
		@else
			Add Books On Behalf Of User
		@endif
		</span><br/>
	<br/>
	{{ Form::open(array('action' => 'BookController@addBook','files'=>true)) }}
	UserID <span style="color:red">*</span><br/>
	{{ Form::text('UserID', '', ['required','size'=>40,'maxlength'=>100]) }}<br/>
	Title <span style="color:red">*</span><br/>
	{{ Form::text('Title', '', ['required','size'=>40,'maxlength'=>100]) }}<br/>
	Sub Title<br/>
	{{ Form::text('SubTitle', '', ['size'=>40,'maxlength'=>100]) }}<br/>
	Author <span style="font-size:90%">(or editor or series name)</span> <span style="color:red">*</span><br/>
	{{ Form::text('Author1', '', ['required','size'=>40,'maxlength'=>100]) }}<br/>
	Any other authors?<br/>
	{{ Form::text('Author2', '', ['size'=>40,'maxlength'=>100]) }}<br/>
	Language  <span style="color:red">*</span><br/>
	{{ Form::text('Language1', 'English', ['required','maxlength'=>50]) }}<br/>
	Any other language? <span style="font-size:90%">(for multi-lingual books)</span><br/>
	{{ Form::text('Language2', '', ['maxlength'=>50]) }}<br/>
	Book Cover<br/>
	{{ Form::file('book-cover') }}<br/>
	{{ Form::checkbox('ForGiveAway', 1, false, ['style'=>'width:1em;height:1em;']) }} 
	<b>For Give Away</b> <span class="smallHelpText">(tick if you no longer want to own this book and want to give it away)</span><br/>
	{{ Form::submit('Yes, Add', array('class' => 'normalButton')); }}
	{{ Form::close() }}
</div>
@include('templates.addBooks')
</div>

@stop