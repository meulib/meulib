@extends('templates.base')

@section('content')
	@if (Session::has('loggedInUser'))
	
	@else
		@include('templates.loginFormV')
	@endif
@stop
