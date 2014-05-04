
<div id="requestBook<?php echo $copyCount ?>" style="display:none">
<form action="requestBook.php" method="post">
	<input type="hidden" name="bookCopyID" value="<?php echo $bookCopyID ?>">
	Message: <br/>
	<textarea cols="50" rows="5" name="requestMessage" required>Hi,
May I please borrow your book '<?php echo $title ?>'? Please let me know when and where I can meet you to collect the book. 
Thank you!
	</textarea>
	<br/>
	<!-- <img src="tools/showCaptcha.php" alt="captcha" /><br/>
      <label>Please enter these characters</label><br/>
      <input type="text" name="captcha" required /> -->
    <input type="submit" name="request" value="Request" />
</form>
</div>
