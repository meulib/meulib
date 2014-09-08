@extends('templates.base')

@section('content')
<!-- TO DO: break this form into several pages/tabs with heading so that it is easier for user. Form does not feel too long. -->
{{ Form::open(array('action' => 'UserController@submitSignup')) }}
<ul class="errors">
@foreach($errors->all() as $message)
    <li>{{ $message }}</li>
@endforeach
</ul>
	<table>
		<tr>
			<td>{{ Form::label('l_email', 'Email'); }}</td>
			<td>{{ Form::email('email', '', ['required']) }}<br/></td>
		</tr>
		<tr>
			<td>{{ Form::label('l_email1', 'Re-enter Email'); }}</td>
			<td>{{ Form::email('email_confirmation', '', ['required']) }}<br/></td>
		</tr>
		<tr>
			<td>{{ Form::label('l_name', 'Full Name'); }}</td>
			<td>{{ Form::text('name', '', ['required']) }}<br/></td>
		</tr>
		<tr>
			<td style="vertical-align: top;">
				{{ Form::label('l_addr', 'Address'); }}
			</td>
		    <td>{{ Form::textarea('address', '', ['size' => '20x3','required']) }}
		    </td>
		</tr>
		<tr>
			<td>{{ Form::label('l_locality', 'Locality'); }}</td>
			<td>{{ Form::text('locality', '') }}</td>
		</tr>
		<tr>
			<td>{{ Form::label('l_city', 'City'); }}</td>
			<td>{{ Form::select('city', ['Manipal', 'Udupi'],['required']) }}
			</td>
		</tr>
		<tr>
			<td>{{ Form::label('l_state', 'State'); }}</td>
			<td>{{ Form::select('state', ['Karnataka'],['required']) }}
			</td>
		</tr>
		<tr>
			<td>{{ Form::label('l_phone', 'Phone number'); }}<br/>
				(mobile preferred,<br/>
				landline with STD)</td>
			<td valign="top">{{ Form::text('phone', '') }}<br/></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><br/></td>
		</tr>
		<tr>
			<td>{{ Form::label('l_username', 'Username'); }}</td>
			<td>
			{{ Form::text('username', '', ['required','pattern' => '[a-zA-Z0-9]{2,64}']) }}<br/>
			</td>
		</tr>
		<tr>
			<td>{{ Form::label('l_pwd', 'Password'); }}</td>
			<td>
			{{ Form::password('password', '', ['required', 'autocomplete' => 'off']) }}<br/>
			</td>
		</tr>
		<tr>
			<td>{{ Form::label('pwd1', 'Re-enter Password'); }}</td>
			<td>
			{{ Form::password('password_confirmation', '', ['required', 'autocomplete' => 'off']) }}<br/>
			</td>
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
			<td>{{ Form::submit('Join!'); }}</td>
		</tr>
	</table>
{{ Form::close() }}
@stop
