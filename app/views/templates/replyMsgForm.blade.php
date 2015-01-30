<div id=replyMessage{{{$tran->ID}}} style="display:none;margin-top:10px;" class='formDiv'>
{{ Form::open(array('action' => 'TransactionController@reply')) }}
	{{ Form::hidden('toUserID',$msg->OtherUserID) }}
	{{ Form::hidden('tranID',$tran->ID) }}
	<br/>
	From: Me<br/>
	To: {{{$msg->OtherUser->FullName}}}<br/>
	Message: <br/>
	{{ Form::textarea('msg', '', ['size' => '50x7']) }}
	<br/>
	<!-- <img src="tools/showCaptcha.php" alt="captcha" /><br/>
      <label>Please enter these characters</label><br/>
      <input type="text" name="captcha" required /> -->
    {{ Form::submit('Reply', array('class' => 'normalButton')); }}
{{ Form::close() }}
</div>
