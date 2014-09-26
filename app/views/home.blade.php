@extends('templates.base')

<?php
	$msg = false;
	if (Session::has('LoginMessage'))
	{
		$msg = Session::get('LoginMessage');
		Session::forget('LoginMessage');
	}
?>

@section('content')
	@if (Session::has('loggedInUser'))
	
	@else
		@if ($msg)
			Please login to access {{{$msg['from']}}}.<br/><br/>
		@endif
		@include('templates.loginFormV')
	@endif
@stop
