@extends('templates.base')

@section('content')

<div style="margin: 0 auto; display:table;" id="loginDiv">
	@if (isset($result))
		{{$result[1]}}<br/>
		<br/>
	@endif
	
	
	@include('templates.loginFormV')
</div>

@stop