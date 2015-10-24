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
	$randomPromo = rand(1,9);
	//$randomPromo = 5;
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
		<div style="max-width:100%;text-align:center;font-size:120%;font-weight:bold">
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
			Inundated with books? Give away the ones you no longer want.<br/>
			{{ HTML::image('images/promo/pikuFB.jpg','',array('width'=>600,'style'=>'max-width:100%')) }}<br/>
			<span style="font-size:80%">Image taken from the movie "Piku".</span>
		@endif
		@if ($randomPromo == 6)
			What a book means to us. Word-o-graphic <a href="https://www.facebook.com/groups/meulib" target="_blank">co-created by 
			MeULib members and others</a>.<br/>
			{{ HTML::image('images/promo/book-word-vis.png','',array('width'=>600,'style'=>'max-width:100%')) }}
		@endif
		@if ($randomPromo == 7)
			<a href="http://meulib.com/blog/books-depreciate/">
			<img src="http://meulib.com/blog/wp-content/uploads/2015/08/cars-books-veg2-825x510.png" width=600 style="max-width:100%" /><br/>
			<b>The MeULib Blog's September post.</b>
			</a>
		@endif
		@if ($randomPromo == 8)
			<a href="http://meulib.com/blog/books-on-our-journey-post/">
				<b>Here I was, reading the gripping Alistair Maclean novel ...</b><br/>
			<img src="http://meulib.com/blog/wp-content/uploads/2015/09/st8-825x510.jpg" width=600 style="max-width:100%" /><br/>
			
			</a>
		@endif
		@if ($randomPromo == 9)
			<a href="http://meulib.com/blog/books-on-our-journey-post/">
				<b>Launching the Books On Our Journey article series ...</b><br/>
			<img src="http://meulib.com/blog/wp-content/uploads/2015/09/st8-825x510.jpg" width=600 style="max-width:100%" /><br/>
			
			</a>
		@endif
		</div>
	</div>
	
	</div>
@stop
