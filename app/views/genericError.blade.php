
@extends('templates.base')

@section('content')

<p align="center">
	@foreach($errors->all() as $message)
    	{{ $message }}</br>
	@endforeach
</p>

@stop

