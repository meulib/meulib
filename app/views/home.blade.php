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
	$randomPromo = rand(1,5);
?>

@section('content')
	<div class="contentDiv">
	<!--div class="homeLoginWelcome" style="display:inline-block;"-->
	<div class="homeLeft">
		@if (Session::has('loggedInUser'))
			<?php
		        $user = Session::get('loggedInUser');
		    ?>
			<p align="center">
			<b>Welcome!</b><br/>
			<br/>
			You can now<br/>
			<br/>
			<a href={{URL::to('/browse')}}>Browse Collection</a> and request to borrow books<br/>
			<br/>
			<!-- Add Books you are willing to lend<br/> -->
			{{HTML::link(URL::to('/'.$user->Username), 'Manage your books')}} on {{ $appName }}<br/>
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
		<!-- blockquote class="twitter-tweet" data-partner="tweetdeck"><p lang="en" dir="ltr">Looks like <a href="https://twitter.com/hashtag/Piku?src=hash">#Piku</a> is inundated. Are you too?&#10;Give away books you no longer want on <a href="http://t.co/KEDcFfBxth">http://t.co/KEDcFfBxth</a> <a href="http://t.co/KKRskLAuai">pic.twitter.com/KKRskLAuai</a></p>&mdash; Meulib (@meulib) <a href="https://twitter.com/meulib/status/595796037199110144">May 6, 2015</a></blockquote>
		<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script -->
		<div style="max-width:100%;text-align:center">
		@if ($randomPromo == 1)
			<a href={{ URL::to('/how-it-works-owner') }}>
			{{ HTML::image('images/howitworks/o1.png','',array('width'=>287, 'height'=>246,
						'style'=>'max-width:100%')) }}
			<br/>
			See how {{Config::get('app.name')}} works<br/>
			for the book owner</a>
		@endif
		@if ($randomPromo == 2)
			{{HTML::image('images/promo/vision.png','',array('style'=>'max-width:100%'))}}
		@endif
		@if ($randomPromo == 3)
			<a href={{ URL::to('/how-it-works-borrower') }}>
			{{ HTML::image('images/howitworks/b1.png','',array('width'=>287, 'height'=>246,
					'style'=>'max-width:100%')) }}
			<br/>
			See how {{Config::get('app.name')}} works<br/>
			for the borrower</a>
		@endif
		@if ($randomPromo == 4)
			{{ HTML::image('images/promo/directLending.png','',array('style'=>'max-width:100%')) }}
		@endif
		@if ($randomPromo == 5)
			Looks like Piku is inundated. Are you too?<br/>
			Give away books you no longer want.<br/>
			{{ HTML::image('images/promo/pikuFB.jpg','',array('width'=>600,'style'=>'max-width:100%')) }}
		@endif
		</div>
	</div>
	
	</div>
@stop
