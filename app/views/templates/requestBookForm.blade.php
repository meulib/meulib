<div id=requestBook{{{$bCopy->ID}}} style="display:none" class="formDiv">
{{ Form::open(array('action' => 'TransactionController@request')) }}
	{{ Form::hidden('bookCopyID',$bCopy->ID) }}
	Message: <br/>
	<span class="smallHelpText">The text below is just a suggestion. Feel free to 
		contact the owner using your own words.<br/></span>
	<?php 
	if ($bCopy->Owner->LocationID == $loggedInUser->LocationID)
	{
		if ($bCopy->ForGiveAway)
			$msg = "Hi, \nI would like to take your book '".$title."'.\nWe are in the same ".
				"city. I could come and take it from you or you could send it to me if you ".
				"prefer. Please let me know.\n".
				"Thank you!" ;
		else
			$msg = "Hi, \nI would like to borrow your book '".$title."'.\nWe are in the ".
				"same city. I could come and take it from you or you could send it to me ".
				"if you prefer. Please let me know.\n".
				"Thank you!" ;
	}
	else // different city
	{
		if ($bCopy->ForGiveAway)
			$msg = "Hi, \nI would like to take your book '".$title."'.\nWe are in different ".
				"cities. Will you please send it to me? I am willing to pay for postage.\n".
				"Please let me know.\n".
				"Thank you!" ;
		else
			$msg = "Hi, \nI would like to borrow your book '".$title."'.\nWe are in ".
				"different cities. Will you lend it outside your city? I am willing to pay ".
				"for postage.\nPlease let me know.\n".
				"Thank you!" ;
	}
	
	?>
	{{ Form::textarea('requestMessage', $msg, ['size' => '50x7']) }}
	<br/>
	<!-- <img src="tools/showCaptcha.php" alt="captcha" /><br/>
      <label>Please enter these characters</label><br/>
      <input type="text" name="captcha" required /> -->
    {{ Form::submit('Request', array('class' => 'normalButton')) }}
{{ Form::close() }}
</div>
