<div class="loginFormH">
{{ Form::open(array('action' => 'UserController@login')) }}
	@if (isset($msg))
		{{ Form::hidden('fromURL', $msg['fromURL']) }}
	@else
		{{ Form::hidden('fromURL', '') }}
	@endif
	<div class="loginFormHUname" style="display:inline-block;padding-left:2px;">
		{{ Form::label('user_name', 'Username'); }}
		{{ Form::text('user_name', 'username or email'); }} 
	</div>
	<div class="loginFormHPwd" style="display:inline-block;padding-left:2px;">
		{{ Form::label('user_password', 'Password'); }}
		{{ Form::password('user_password'); }}
		{{ Form::submit('Login'); }}
	</div>
{{ Form::close() }}
</div>