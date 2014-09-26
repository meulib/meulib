{{ Form::open(array('action' => 'UserController@login')) }}
	@if (isset($msg))
		{{ Form::hidden('fromURL', $msg['fromURL']) }}
	@else
		{{ Form::hidden('fromURL', '') }}
	@endif
	{{ Form::label('user_name', 'Username'); }}
	{{ Form::text('user_name', 'username or email'); }} 
	{{ Form::label('user_password', 'Password'); }}
	{{ Form::password('user_password'); }}
	{{ Form::submit('Login'); }}
{{ Form::close() }}