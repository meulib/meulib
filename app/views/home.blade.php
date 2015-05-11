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
		<blockquote class="twitter-tweet" data-partner="tweetdeck"><p lang="en" dir="ltr">Looks like <a href="https://twitter.com/hashtag/Piku?src=hash">#Piku</a> is inundated. Are you too?&#10;Give away books you no longer want on <a href="http://t.co/KEDcFfBxth">http://t.co/KEDcFfBxth</a> <a href="http://t.co/KKRskLAuai">pic.twitter.com/KKRskLAuai</a></p>&mdash; Meulib (@meulib) <a href="https://twitter.com/meulib/status/595796037199110144">May 6, 2015</a></blockquote>
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
	</div>
	
	</div>
@stop
