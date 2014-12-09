
@extends('templates.base')

@section('content')

<div style="margin: 0 auto; display:table;" id="loginDiv">
	@foreach($errors->all() as $message)
    	{{ $message }}</br>
	@endforeach
	<b>Reset Password</b><br/><br/>
	{{ Form::open(array('action' => 'UserController@resetPwd')) }}
	{{ Form::hidden('id', $id) }}
	{{ Form::hidden('resetCode', $resetCode) }}
	<table>
		<tr>
			<td>{{ Form::label('l_pwd', 'New Password'); }}</td>
			<td>
			{{ Form::password('password', '', ['required', 'autocomplete' => 'off']) }}<br/>
			</td>
		</tr>
		<tr>
			<td>{{ Form::label('pwd1', 'Re-enter Password'); }}</td>
			<td>
			{{ Form::password('password_confirmation', '', ['required', 'autocomplete' => 'off']) }}<br/>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>{{ Form::submit('Reset'); }}</td>
		</tr>
	</table>
	{{ Form::close() }}
</div>

@stop