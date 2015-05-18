
@extends('templates.base')

@section('title', "Members in ".$country." " )


@section('content')


<!-- === COUNTRY LISTING === -->
{{HTML::link(URL::route('member-browse'), 'Back to all countries')}}
<div style="margin-bottom:2px">
	<div class="bookMat">
		{{{ $country }}}
		{{ HTML::image('images/country-pics/'.$country.'.png', '',array('width'=>180)) }}
	</div>
	<div style="display:inline-block;text-align:center">
		@foreach($cities as $city)
			<a href=#{{$city->Location}}>{{$city->Location}}</a> {{$city->TotalMembers > 1? ": " . $city->TotalMembers . " members" : ""}}<br/>
		@endforeach
	</div>
</div>

<!-- === CITY-WISE MEMBERS === -->
@if (count($cities) > 0)
	<div style="display:block;">
	@foreach($cities as $city)
		<a name={{$city->Location}}></a>
		<div style="display:inline-block;border:1px solid #B48700;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;padding-left:2px;margin-bottom:2px">
		{{$city->Location}} {{$city->TotalMembers > 2? ": " . $city->TotalMembers . " members" : ""}}<br/>
		<?php $slicedMembers = array_slice($members,$citiesWithOffset[$city->ID]["OffsetStart"],$city->TotalMembers); ?>
		@foreach($slicedMembers as $member)
			<div class="memberMat">
				@if (strlen($member->ProfilePicFile)>0)
					<div class="memberPicture" style="background-image: url('{{Config::get('app.url')}}/images/member-pics/{{$member->ProfilePicFile}}')">
				@else
					<div class="memberPicture" style="background-image: url('{{Config::get('app.url')}}/images/member-pics/meulib_member.png')">
				@endif
	    		</div>
	    		{{-- HTML::image('images/member-pics/'.$member->ProfilePicFile, 'a picture', array('width' => '50','height' => '50')) --}}
				{{ HTML::link(URL::route('user-books',$member->Username), $member->FullName)}}
			</div>
		@endforeach
		</div>
		<!-- br/><br/ -->
	@endforeach
	</div>
@endif

@stop