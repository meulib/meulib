
@extends('templates.base')

@section('title', 'How MeULib Works for the Owner: ')

@section('content')

<div style='font-size:150%;font-weight:bold;margin:20px auto;display:table;text-align:center'>
	How {{Config::get('app.name');}} Works<br/>
	Owner Side<br/>
	<br/>

	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o1.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o2.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o3.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o5.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o7.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o8.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o9.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o10.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o11.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o12.png','') }}
	</div>
	<!--
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/b0.png','',array('width' => '239', 'height' => '204')) }}
	</div>
	-->
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/o13.png','') }}
	</div>
	<div style="display:inline-block;">
		{{ HTML::image('images/howitworks/mu.png','') }}
	</div>

	<br/>
	<a href={{ URL::to('/how-it-works-borrower') }}>Also See Borrower Side</a>

</div>

@stop

