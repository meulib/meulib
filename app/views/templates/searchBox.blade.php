<div class="searchContainer">
	{{ Form::open(array('action' => 'BookController@search','method'=>'get')) }}
	<input type="text" name="s" class="searchBox" placeholder="Search Book Title, Author" />
	{{-- <button style="background-color:transparent;border:0;background:hsla(197,100%,92%,1) url(1447869415_magnifying-glass-search.png) 0 0 no-repeat;width:30px;height:30px;float:right">&nbsp;</button> --}}
	{{ Form::submit(' ', 
			array('class' => 'searchButton',
				'style' => 'background:hsla(197,100%,92%,1) url(1447869415_magnifying-glass-search.png) 0 0 no-repeat;width:30px;')); }}
	{{ Form::close() }}
</div>
