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
?>

@section('content')
	<div class="contentDiv">
	<div class="homeLoginWelcome" style="display:inline-block;">
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
			@else
				<br/>
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
	<div style="display:inline-block;vertical-align: top;">
		<p align="center">
			<b>What's new in {{ $appName }}?</b><br/>
			<br/>
			@foreach ($whatsNew as $singleNew)
				{{$singleNew}}<br/><br/>
			@endforeach
		</p>
	</div>
	</div>
@stop
