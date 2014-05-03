<form action="postMessage.php" method="post">
	<input type="hidden" name="toUserID" value="<?php echo $toUserID; ?>">
	<input type="hidden" name="tranID" value="<?php echo $tranID; ?>">
	Message: <br/>
	<textarea cols="80" rows="5" name="msg" required></textarea>
	<br/>
    <input type="submit" name="reply" value="Reply" />
</form>
