<?php

class Postman {

	public static function mailToAdmin($subject,$bodyText)
	{
		$useQueue = Config::get('mail.useQueue');
		$body = array('body'=>$bodyText);
		$result = false;
		if ($useQueue)
		{
			$result = self::mailAdminByQueue($subject,$body);
		}
		else
		{
			$result = self::mailAdminDirect($subject,$body);
		}
		return $result;
	}

	/*
	$view = name of email view
	$viewData = data array required by view

	$to = array('email'=>,'name'=>)
	i.e. array with two elements of keys 'email', 'name'
	
	$fromPersonalizeAdmin: true / false
	Will the from header say an impersonal "App Admin" or 
	will it say a human name as configured in 
	config/mail.php setting: fromHuman
	*/

	public static function mailToUser($view,$viewData,$subject,$to,$fromPersonalizedAdmin=false)
	{
		$from = [];
		$from['email'] = Config::get('mail.from')['address'];
		if ($fromPersonalizedAdmin)
			$from['name'] = Config::get('mail.fromHuman');
		else
			$from['name'] = Config::get('mail.from')['name'];
		$useQueue = Config::get('mail.useQueue');
		$result = false;
		if ($useQueue)
		{
			$result = self::mailUserByQueue($view,$viewData,$subject,$to,$from);
		}
		else
		{
			$result = self::mailUserDirect($view,$viewData,$subject,$to,$from);
		}
		return $result;
	}

	private static function mailUserDirect($view,$viewData,$subject,$to,$from)
	{
		try
		{
			Mail::send($view,$viewData, function($message) use ($subject,$to,$from)
			{
				$message->to($to['email'], $to['name'])
						->subject($subject)
						->from($from['email'],$from['name']);
			});
			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	private static function mailUserByQueue($view,$viewData,$subject,$to,$from)
	{
		try
		{
			Mail::queue($view,$viewData, function($message) use ($subject,$to,$from)
			{
				$message->to($to['email'], $to['name'])
						->subject($subject)
						->from($from['email'],$from['name']);
			});
			return true;
		}
		catch (Exception $e)
		{
			self::mailUserDirect($view,$viewData,$subject,$to,$from);
		}
	}

	private static function mailAdminDirect($subject,$body)
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

	private static function mailAdminByQueue($subject,$body)
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
			self::mailAdminDirect($subject,$body);
		}
			
	}

}

?>