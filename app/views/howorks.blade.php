
@extends('templates.base')

@section('title', 'How MeULib Works: ')

@section('content')

<div style='font-size:150%;font-weight:bold;margin:0 auto;display:table;'>
	<div style='display:table;margin:20px auto'>
		How {{Config::get('app.name');}} Works
	</div>
	<div style='display:table;margin:0 auto'>
	<div style="vertical-align:top;display:inline-block;text-align:center;margin-right:20px;">
		<a href={{ URL::to('/how-it-works-owner') }}>
		Owner Side<br/>
		{{ HTML::image('images/howitworks/ofull.png','') }}
		</a>
	</div>
	<div style="vertical-align:top;display:inline-block;text-align:center;">
		<a href={{ URL::to('/how-it-works-borrower') }}>
		Borrower Side<br/>
		{{ HTML::image('images/howitworks/bfull.png','') }}
		</a>
	</div>
	</div>
</div>

@stop

