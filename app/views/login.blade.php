@extends('templates.base')

@section('title', 'Login: ')

@section('content')

<div style="margin: 0 auto; display:table;" id="loginDiv">
	@if (isset($result))
		{{$result[1]}}<br/>
		<br/>
	@endif
	
	<div class='formDiv'>
		@include('templates.loginFormV')
	</div>
</div>

@stop