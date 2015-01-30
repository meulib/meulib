<?php

class AppMailer {

	public static function MailToAdmin($subject,$bodyText)
	{
		$useQueue = Config::get('mail.useQueue');
		$body = array('body'=>$bodyText);
		$result = false;
		if ($useQueue)
		{
			$result = self::MailQueue($subject,$body);
		}
		else
		{
			$result = self::MailDirect($subject,$body);
		}
	}

	private static function MailDirect($subject,$body)
	{
		try
		{
			Mail::send(array('text' => 'emails.raw'), $body, function($message) use ($subject)
			{
				$message->to(Config::get('mail.admin'))
						->subject($subject);
			});
			return true;	
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	private static function MailQueue($subject,$body)
	{
		$subject .= ' (by q)';
		try
		{
			Mail::queue(array('text' => 'emails.raw'), $body, function($message) use ($subject)
			{
				$message->to(Config::get('mail.admin'))
						->subject($subject);
			});
			return true;
		}
		catch (Exception $e)
		{
			self::MailDirect($subject,$body);
		}
			
	}

}

?>