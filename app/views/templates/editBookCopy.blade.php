<div class="formDiv">
	<!-- span class="formTitle">
		@if (isset($addMoreBooks) && ($addMoreBooks))
			Add More Books
		@else
			Add Books That You Are Willing To Lend
		@endif
		</span><br/>
	<br/ -->
	{{ Form::open(array('action' => 'BookController@editBookCopy','files'=>true)) }}
	{{ Form::checkbox('ForGiveAway', 1, false, ['style'=>'width:1em;height:1em;']) }} 
	<b>For Give Away</b> <span class="smallHelpText">(tick if you no longer want to own this book and want to give it away)</span><br/>
	{{ Form::submit('Save', array('class' => 'normalButton')); }}
	{{ Form::button('Cancel', array('class' => 'normalButton','onclick' => $onclickHideEdit)); }}
	{{ Form::close() }}
</div>