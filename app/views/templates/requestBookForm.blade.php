<div id=requestBook{{{$bCopy->ID}}} style="display:none" class="formDiv">
{{ Form::open(array('action' => 'TransactionController@request')) }}
	{{ Form::hidden('bookCopyID',$bCopy->ID) }}
	Message: <br/>
	<?php $msg = "Hi, \nMay I please borrow your book '".$title."'?\nPlease ".
		"let me know when and where I can meet you to collect the book.\n".
		"Thank you!" ?>
	{{ Form::textarea('requestMessage', $msg, ['size' => '50x7']) }}
	<br/>
	<!-- <img src="tools/showCaptcha.php" alt="captcha" /><br/>
      <label>Please enter these characters</label><br/>
      <input type="text" name="captcha" required /> -->
    {{ Form::submit('Request', array('class' => 'normalButton')) }}
{{ Form::close() }}
</div>
