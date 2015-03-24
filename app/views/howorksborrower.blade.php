
@extends('templates.base')

@section('title', 'How MeULib Works for the Borrower: ')

@section('content')

<div style='font-size:150%;font-weight:bold;margin:20px auto;display:table;text-align:center'>
	How {{Config::get('app.name');}} Works<br/>
	Borrower Side<br/>
	<br/>

	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b1.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b2.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b4.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b5.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b6.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b7.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b8.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b10.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b11.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b12.png','') }}
	</div>
	<!--
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b0.png','',array('width' => '239', 'height' => '204')) }}
	</div>
	-->
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b13.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/mu.png','') }}
	</div>

	<br/>
	<a href={{ URL::to('/how-it-works-owner') }}>Also See Owner Side</a>

</div>

@stop

