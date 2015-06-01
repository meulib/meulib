<?php
	$appName = Config::get('app.name');
?>

@extends('templates.base')

@section('title', "Members in ".$country." " )


@section('content')

<div class="contentDiv">

<!-- === COUNTRY LISTING === -->
<div id="fixedwidth" style="text-align:center">
	<div style="margin-bottom:2px">
		{{HTML::link(URL::route('member-browse'), 'Back to all countries')}}
		<div class="bookMat">
			{{{ $country }}}
			{{ HTML::image('images/country-pics/'.$country.'.png', '',array('width'=>180)) }}
		</div>
		<div style="display:inline-block;text-align:center;background-color: #f0f9f0;margin:0 auto;">
			@foreach($cities as $city)
				<a href=#{{$city->Location}}>{{$city->Location}}</a> {{$city->TotalMembers > 1? ": " . $city->TotalMembers . " members" : ""}}<br/>
			@endforeach
		</div>
	</div>
</div> <!-- end fixed -->

<!-- === CITY-WISE MEMBERS === -->
@if (count($cities) > 0)
	<div id="fluid">
	<div id="inner-block">
	@foreach($cities as $city)
		<a name={{$city->Location}}></a>
		<div style="display:inline-block;background-color: #f0f9f0;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;padding-left:2px;margin-bottom:2px">
		{{$city->Location}} {{$city->TotalMembers > 2? ": " . $city->TotalMembers . " members" : ""}}<br/>
		<?php $slicedMembers = array_slice($members,$citiesWithOffset[$city->ID]["OffsetStart"],$city->TotalMembers); ?>
		@foreach($slicedMembers as $member)
			<div class="memberMat">
				@if (strlen($member->ProfilePicFile)>0)
					<div class="memberPicture" style="background-image: url('{{Config::get('app.url')}}/images/member-pics/{{$member->ProfilePicFile}}')"></div>
				@else
					<div class="memberPicture" style="background-image: url('{{Config::get('app.url')}}/images/member-pics/meulib_member.png')"></div>
				@endif
	    		{{-- HTML::image('images/member-pics/'.$member->ProfilePicFile, 'a picture', array('width' => '50','height' => '50')) --}}
				{{ HTML::link(URL::route('user-books',$member->Username), $member->FullName)}}
				@if (strlen($member->ClaimToFame)>0)	
					{{{str_replace('appName', $appName, $member->ClaimToFame)}}}
				@endif
			</div>
		@endforeach
		</div>
		<!-- br/><br/ -->
	@endforeach
	</div>	<!-- end inner-block -->
	</div>  <!-- end fluid -->
@endif

<br style="clear:both">

</div> <!-- end content -->

@stop