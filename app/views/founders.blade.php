
@extends('templates.base')

@section('content')
<div class='contentDiv' style='text-align:center'>
	<div class='pageTitle'>
		Founding Members
	</div>
	@foreach($founders as $founder)
	<div class="memberMat">
		@if (strlen($founder->PictureFile)>0)
			<div class="memberPicture" style="background-image: url('images/member-pics/{{$founder->PictureFile}}')">
		@else
			<div class="memberPicture" style="background-image: url('images/member-pics/meulib_member.png')">
		@endif
	    {{-- HTML::image('images/member-pics/'.$founder->PictureFile, 'a picture', array('width' => '50','height' => '50')) --}}
		</div>
		<!-- br/ -->
		@if ($founder->UserDetails)
			{{ HTML::link(URL::route('user-books',$founder->UserDetails->Username), $founder->UserDetails->FullName)}}
		@else
			{{$founder->Name}}
		@endif
		<br/>
		{{$founder->ClaimToFame}}
	</div>
	@endforeach
	<p align="center">
		<br/><br/>
		You can be a part of this too.
		<br/>
		There are many ways to be a founding member.<br/>
		Participate and you will discover.</p>
	</div>
@stop