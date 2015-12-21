@extends('templates.base')

@section('title', 'Browse Collection: ')

@section('content')
@include('templates.searchBox')
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
