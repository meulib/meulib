{{ Form::open(array('action' => 'UserController@login')) }}
	@if (isset($msg))
		{{ Form::hidden('fromURL', $msg['fromURL']) }}
	@else
		{{ Form::hidden('fromURL', '') }}
	@endif
	<table>
		<tr><td>
			{{ Form::label('user_name', 'Username'); }}
		</td><td>
			{{ Form::text('user_name', 'username or email'); }}
		</td></tr>
		<tr><td>
			{{ Form::label('user_password', 'Password'); }}
		</td><td>
			{{ Form::password('user_password'); }}<br/>
		</td></tr>
		<tr><td></td><td>
			{{ Form::submit('Login'); }}
		</td></tr>
	</table>
{{ Form::close() }}