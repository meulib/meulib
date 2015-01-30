<b>Login</b>
{{ Form::open(array('action' => 'UserController@login')) }}
	@if (isset($fromURL))
		{{ Form::hidden('fromURL', $fromURL) }}
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
			{{ Form::submit('Login', array('class' => 'normalButton')); }}
		</td></tr>
	</table>
{{ Form::close() }}
<br/>
<a href={{URL::to('forgot-password')}}>Forgot your password?</a>