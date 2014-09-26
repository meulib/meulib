<div id='head' style='position:relative;width:100%;margin-bottom:10px;'>
	<div id='sitetitle' style='width:50%;'>
		<a href={{URL::to('/')}}>
		<h1>
		{{ HTML::image(Config::get('app.logoUrl'),Config::get('app.name') . 'Logo',array('style' => 'vertical-align:middle;')) }}
		@if (Config::get('app.displayNameOnMast'))
			{{Config::get('app.name')}}
		@endif
		</h1>
		</a>
		<b>{{Config::get('app.tag_line')}}</b>
	</div>
	<div id='toplinks' style='width:50%;position:absolute;right:0;top:0;text-align:right'>
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