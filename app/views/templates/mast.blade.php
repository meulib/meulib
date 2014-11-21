<div class='head'>
	<!-- div class='siteTitle' style='width:50%;'-->
	<div class='siteTitle'>
		<a href={{URL::to('/')}}>
			@if (Config::get('app.displayNameOnMast'))
				<h1>
				{{ HTML::image(Config::get('app.logoUrl'),Config::get('app.name') . 'Logo',array('style' => 'vertical-align:middle;')) }} 
				{{Config::get('app.name')}}
				</h1>
			@else
				{{ HTML::image(Config::get('app.logoUrl'),Config::get('app.name') . 'Logo',array('style' => 'vertical-align:middle;')) }}
				<br/>
			@endif
		</a>
		<b>{{Config::get('app.tag_line')}}</b>
	</div>
	<!-- topLinks style='width:50%;position:absolute;right:0;top:0;text-align:right' -->
	<div class='topLinks'>
		@if (Session::has('loggedInUser'))
			Hello 
			<?php
				$user = Session::get('loggedInUser');
				echo $user->FullName;
			?> | 
			<a href={{URL::action('UserController@logout')}}>Logout</a>
		@else
			@include('templates.loginFormH')
		@endif
	</div>
</div>