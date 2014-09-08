{{ Form::open(array('action' => 'UserController@login')) }}
	{{ Form::label('user_name', 'Username'); }}
	{{ Form::text('user_name', 'username or email'); }}<br/>
	{{ Form::label('user_password', 'Password'); }}
	{{ Form::password('user_password'); }}<br/>
	{{ Form::submit('Login'); }}
{{ Form::close() }}