
@extends('templates.base')

@section('title', "Members " )


@section('content')


<!-- === COUNTRY LISTING === -->
<div style="display:table;margin:0 auto">
@if (count($countries) > 0)
	@foreach($countries as $country)
		<div class="bookMat">
			<a href={{URL::route("member-browse",array($country->Country))}}>{{{ $country->Country }}}</a>
			<div class="bookMatAuthor">
				<a href={{URL::route("member-browse",array($country->Country))}}>{{ $country->TotalMembers . ' members'}}</a>
			</div>
			<a href={{URL::route("member-browse",array($country->Country))}}>{{ HTML::image('images/country-pics/'.$country->Country.'.png', '',array('width'=>180)) }}</a>
		</div>				
	@endforeach
@endif
</div>

@stop