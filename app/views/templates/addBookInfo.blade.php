<div class="formDiv">
	<a name="AdditionalInfo"></a>
	<div class="formTitle">
		Which categories does<br/>
		{{ $book->Title }}<br/>
		belong to?
	</div>
	<br/>
	<br/>
	{{ Form::open(array('action' => 'BookController@setBookInfo')) }}
	{{ Form::hidden('bookID',$book->ID) }}
	Book Categories:<br/>
	(select as many are applicable, use Ctrl)<br/>
	{{ Form::select('CategoryID[]', $categories, 
	   null, ['size' => '10','multiple' => true]) }}<br/>
	Suggest other categories <br/>
	(separate multiple categories by comma)<br/>
	{{ Form::text('SuggestedCategories', '', ['size'=>40,'maxlength'=>500]) }}<br/>
	{{ Form::submit('Submit'); }}
	{{ Form::close() }}
</div>