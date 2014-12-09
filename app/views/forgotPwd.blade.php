
@extends('templates.base')

@section('content')

<?php 
$success = false; 
$msg = '';
if (isset($result))
{
	//var_dump($result);
	$msg = $result[1]."<br/><br/>";
	if ($result[0])
		$success = true;
}
?>

<div style="margin: 0 auto; display:table;" id="forgotPwdDiv">
	{{ $msg }}
	@if (!$success)
	<b>Forgot your password?</b><br/>
	<br/>
		Enter Email Address<br/>
		to reset password:
		{{ Form::open(array('action' => 'UserController@forgotPwd')) }}
			{{ Form::text('email', ''); }}<br/>
			{{ Form::submit('Get Password Reset Link'); }}
		{{ Form::close() }}
	@endif
</div>

@stop