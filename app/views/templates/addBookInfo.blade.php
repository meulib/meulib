<div class="formDiv">
	<a name="AdditionalInfo"></a>
	<span class="formTitle">
		Categories for {{ $book->Title }}
	</span>
	<br/><br/>
	{{ Form::open(array('action' => 'BookController@setBookInfo')) }}
	{{ Form::hidden('bookID',$book->ID) }}
	Select as many as are applicable, use Ctrl<br/>
	{{--
	@foreach ($categories as $category)
		{{Form::checkbox('CategoryID[]', $category->ID)}}
		{{$category->Category}}<br/>
	@endforeach --}}
	{{ Form::select('CategoryID[]', $categories, 
	   null, ['size' => '10','multiple' => true]) }}<br/>
	Suggest other categories <br/>
	(separate multiple categories by comma)<br/>
	{{ Form::text('SuggestedCategories', '', ['size'=>40,'maxlength'=>500]) }}<br/>
	{{ Form::submit('Submit Categories', array('class' => 'normalButton')); }}
	{{ Form::close() }}
</div>