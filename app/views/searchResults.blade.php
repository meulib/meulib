@extends('templates.base')

@section('title', 'Browse Collection: ')

@section('content')
<div class="searchContainer">
	{{ Form::open(array('action' => 'BookController@search','method'=>'get')) }}
	<input type="text" name="s" class="searchBox" placeholder="Search Books" />
	{{-- <button style="background-color:transparent;border:0;background:hsla(197,100%,92%,1) url(1447869415_magnifying-glass-search.png) 0 0 no-repeat;width:30px;height:30px;float:right">&nbsp;</button> --}}
	{{ Form::submit(' ', 
			array('class' => 'searchButton',
				'style' => 'background:hsla(197,100%,92%,1) url(1447869415_magnifying-glass-search.png) 0 0 no-repeat;width:30px;')); }}
	{{ Form::close() }}
</div>
<div class="contentDiv" style="width:100%">
@if (($books) && (count($books) > 0))
	<span style="font-size:120%">{{count($books) ." results for "}}<b>{{$term}}</b></span><br/>
	@foreach($books as $book)
		<div class="bookMat">
			<a href={{  URL::route('single-book', array($book['ID']))}}>
				@if (strlen($book['CoverFilename'])>0)
					{{ HTML::image('images/book-covers/'.$book['CoverFilename'], 'a picture', array('height' => '150')) }}<br/>
				@endif
				{{{ $book['Title'] }}}
				@if (strlen($book['SubTitle'])>0)
					<div class="bookMatSubTitle">
						{{{ $book['SubTitle'] }}}
					</div>
				@endif
			</a>
			<div class="bookMatAuthor">
				{{{ $book['Author1'] }}}
				@if (strlen($book['Author2'])>0)
					{{{ ", ".$book['Author2'] }}}
				@endif
			</div>
		</div>
	@endforeach
@else
	<span style="font-size:120%">No results for <b>{{$term}}</b></span><br/>
@endif
	<br/>
	<span style="font-size:120%">{{HTML::link(URL::route('browse'), 'Browse Full Collection')}}</span>
</div>
@stop
