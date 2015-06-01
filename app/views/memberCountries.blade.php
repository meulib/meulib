
@extends('templates.base')

@section('title', "Members " )


@section('content')

<div class="contentDiv" style="text-align:center">

<div class="pageTitle">All Members</div>

<!-- === COUNTRY LISTING === -->
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
<br/><br/>
<b>{{ HTML::link(URL::route('founders'), 'Founding Members')}}</b>
</div>

@stop