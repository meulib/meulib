@extends('templates.base')

<?php
	$result = '';
	if (Session::has('result'))
	{
		$result = Session::get('result');
		Session::forget('result');
	}
?>

@section('content')

<div style='margin:0 auto;display:table;max-width:700px;'>
	<div class='pageTitle'>
		Let Us Support You
	</div>

@if (($result != '') && ($result[0]))
	Thank you for writing to us.<br/>
	We will get back to you as soon as possible.
@else

	Send in your questions, suggestions, issues you are facing.
	<br/>
	We are a friendly sort. Eager to hear from you!
<div class='formDiv'>
{{ Form::open(array('action' => 'UtilityController@submitContactForm')) }}
<ul class="errors">
@foreach($errors->all() as $message)
    <li>{{ $message }}</li>
@endforeach
</ul>
	<table>
		<tr>
			<td align="right">{{ Form::label('l_name', 'Name'); }}</td>
			<td>{{ Form::text('name', '', ['required']) }}<br/></td>
		</tr>
		<tr>
			<td align="right">{{ Form::label('l_email', 'Email'); }}</td>
			<td>{{ Form::email('email', '', ['required']) }}<br/></td>
		</tr>
		<tr>
			<td align="right" valign="top">Your message<br/>to say</td>
			<td>{{ Form::textarea('message', '', ['required']) }}<br/></td>
		</tr>
		<tr>
			<td valign="top"></td>
			<td>
				{{ HTML::image(URL::to('showCaptcha')) }}<br/>
      			<label>Please enter these characters</label><br/>
      			<input type="text" name="captcha" required /></td>
		</tr>
		<tr>
			<td></td>
			<td>{{ Form::submit('Yes! Send it in!', array('class' => 'normalButton')); }}</td>
		</tr>
	</table>
{{ Form::close() }}
</div>
@endif

</div>

@stop
