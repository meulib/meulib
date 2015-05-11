
@extends('templates.base')

@section('title', "Members in ".$country." " )


@section('content')


<!-- === COUNTRY LISTING === -->
<div class="bookMat">
	{{{ $country }}}
	{{ HTML::image('images/country-pics/'.$country.'.png', '',array('width'=>180)) }}
</div>

@if (count($cities) > 0)
	<div style="display:inline-block">
	@foreach($cities as $city)
		{{$city->Location}}: {{$city->TotalMembers}} members<br/>
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
		<br/><br/>
	@endforeach
	</div>
@endif

@stop