
@extends('templates.base')

@section('title', 'How MeULib Works: ')

@section('content')

<div style='font-size:150%;font-weight:bold;margin:0 auto;display:table;'>
	<div style='margin:10px'>
	<div style="text-align:center">
		<div style="vertical-align:top;display:inline-block;text-align:center;margin-right:20px;">
			<a href={{ URL::to('/how-it-works-owner') }}>
			How It Works<br/>
			For The Book Owner<br/>
			{{ HTML::image('images/howitworks/o1.png','') }}
			</a>
		</div>
		<div style="vertical-align:top;display:inline-block;text-align:center;">
			<a href={{ URL::to('/how-it-works-borrower') }}>
			How It Works<br/>
			For The Borrower<br/>
			{{ HTML::image('images/howitworks/b1.png','') }}
			</a>
		</div>
	</div>
	<div style="text-align:center">
		<div style="vertical-align:top;display:inline-block;text-align:center;">
			<a href={{ URL::route('membership-rules') }}>
			Membership Rules<br/>
			{{ HTML::image('images/howitworks/clipboard.png','') }}
			</a>
		</div>
		<div style="vertical-align:top;display:inline-block;text-align:center;">
			<a href={{ URL::route('faq') }}>
			FAQ<br/>
			{{ HTML::image('images/howitworks/faq.png','') }}
			</a>
		</div>
	</div>
	</div>
</div>

@stop

