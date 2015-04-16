<div class='blogHead'>
	<!-- div class='siteTitle' style='width:50%;'-->
	<div class='blogTitle'>
		<!-- a href={{URL::to('/')}} -->
			{{ Config::get('app.blog_name') }}
		<!-- /a -->
	</div>
	<div class="blogSubTitle">{{Config::get('app.blog_tag')}}</div>
	<!-- topLinks style='width:50%;position:absolute;right:0;top:0;text-align:right' -->
	<div class='topLinks'>
		<div style='font-size:120%;margin-bottom:2px;'>
			{{-- HTML::link(URL::to('/how-it-works'), 'How It Works') --}}
		</div>
		@if (Session::has('loggedInUser'))
			Hello 
			<?php
				$user = Session::get('loggedInUser');
				echo $user->FullName;
			?> | 
			<a href={{URL::action('UserController@logout')}}>Logout</a>
		@endif
	</div>
</div>