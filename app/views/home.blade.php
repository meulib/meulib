@extends('templates.base')

<?php
	$msg = false;
	if (Session::has('LoginMessage'))
	{
		$msg = Session::get('LoginMessage');
		Session::forget('LoginMessage');
		$fromURL = $msg['fromURL'];
	}
	$appName = Config::get('app.name');
	$whatsNew = Config::get('app.whatsnew');
	$randomPromo = rand(1,4);
?>

@section('content')
	<div class="contentDiv">
	<!--div class="homeLoginWelcome" style="display:inline-block;"-->
	<div class="homeLeft">
		@if (Session::has('loggedInUser'))	
				<p align="center">
				<b>Welcome!</b><br/>
				<br/>
				You can now<br/>
				<br/>
				<a href={{URL::to('/browse')}}>Browse Collection</a> and request to borrow books<br/>
				<br/>
				<!-- Add Books you are willing to lend<br/> -->
				{{HTML::link(URL::to('my-books'), 'Manage your books ')}} on {{ $appName }}<br/>
				<br/>
				{{HTML::link(URL::to('/messages'), 'Check your '.$appName . ' messages')}}<br/>
				<br/>
				{{HTML::link(URL::to('borrowed-books'), 'Manage books borrowed')}} via {{ $appName }}
				</p>
			
		@else
			@if ($msg)
				Please login to access {{{$msg['from']}}}.<br/><br/>
			@endif
			@include('templates.loginFormV')
			<br/><br/>
			<form action={{URL::to('/become-a-member')}}>
				{{ Form::submit('Become a Member', 
					array('class' => 'richButton',
					'name'=>'btnMember')); }}
			</form>
		@endif
	</div>
	<div class="homeRight">
		<!-- p align="center">
			<b>What's new in {{ $appName }}?</b><br/>
			<br/>
			@foreach ($whatsNew as $singleNew)
				{{$singleNew}}<br/><br/>
			@endforeach
		</p-->
		@if ($randomPromo == 1)
			<a href={{ URL::to('/how-it-works-owner') }}>
			{{ HTML::image('images/howitworks/o1.png','',array('width'=>287, 'height'=>246)) }}
			<br/>
			See how {{Config::get('app.name')}} works<br/>
			for the book owner</a>
		@endif
		@if ($randomPromo == 2)
			{{HTML::image('images/promo/vision.png','')}}
		@endif
		@if ($randomPromo == 3)
			<a href={{ URL::to('/how-it-works-borrower') }}>
			{{ HTML::image('images/howitworks/b1.png','',array('width'=>287, 'height'=>246)) }}
			<br/>
			See how {{Config::get('app.name')}} works<br/>
			for the borrower</a>
		@endif
		@if ($randomPromo == 4)
			{{ HTML::image('images/promo/directLending.png','') }}
		@endif
	</div>
	
	</div>
@stop
