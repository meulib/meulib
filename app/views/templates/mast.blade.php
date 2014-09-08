<div id='head' style='position:relative;width:100%;margin-bottom:10px;'>
	<div id='sitetitle' style='width:50%;'>
		<h1>
			<a href={{URL::to('/')}}>{{Config::get('app.name')}}</a>
		</h1>
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
			{{ Form::open(array('action' => 'UserController@login')) }}
				{{ Form::label('user_name', 'Username'); }}
				{{ Form::text('user_name', 'username or email'); }} 
				{{ Form::label('user_password', 'Password'); }}
				{{ Form::password('user_password'); }}
				{{ Form::submit('Login'); }}
			{{ Form::close() }}
		@endif
	</div>
</div>