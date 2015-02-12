<?php

class UserAccess extends Eloquent {

	protected $table = 'user_access';
	public $incrementing = false;
	protected $primaryKey = 'UserID';

	private static function getUserByEmail($email)
	{
		$user = self::where('EMail','=',$email)->first();
		return $user;
	}

	public function getHumanUser()
	{
		$huser = User::where('EMail','=',$this->EMail)->first();
		return $huser;	
	}

	// for activation code and pwd reset code emails
	private static function getSimpleHash()
	{
		return sha1(uniqid(mt_rand(), true));
	}

	private static function generateUserID()
	{
		$found = false;
		$an = "";
		while (!$found)
		{
			$a = chr(rand(1,26)+64).chr(rand(1,26)+64).chr(rand(1,26)+64).chr(rand(1,26)+64);
			$n = mt_rand()*2;
			$an = $a.$n;
			// check User table for unique userID and 
			// not UserAccess because there may be 
			// Users by direct lending in the User table too
			$result = User::where('UserID','=',$an)->count();
			if ($result==0)
				$found = true;
		}
		return $an;
	}

	private static function cryptPwd($plainPwd)
	{
		//$cryptPwd = crypt($pwd,'$5$'.substr(md5(uniqid(rand(),true)),0,13));
		return Hash::make($plainPwd);	// Laravel's method
	}

	public static function Login($userNameEmail, $pwd)
	{
		$user = NULL;

		// user can login with username or email
		if (filter_var($userNameEmail, FILTER_VALIDATE_EMAIL)) // given value is email
			$user = self::getUserByEmail($userNameEmail);
		else
			$user = self::where('Username','=',$userNameEmail)->first();

		if ($user == NULL)
			throw new LoginException("Incorrect Username Email Password", 1);

		// 3 earlier failed login attempts. ask user to wait for 2 minutes
		// to prevent automated tries
		if (($user->FailedLogins >= 3) && ($user->LastFailedLogin > (time() - (60*2))))
            throw new LoginException("Too Many Failed Recent Attempts", 2);

        // verify password
 		//if (!(crypt($pwd,$user->Pwd) == $user->Pwd))
 		if (!(Hash::check($pwd, $user->Pwd)))
 		{
			// increment failed attempts
			$user->FailedLogins += 1;
			$user->LastFailedLogin = time();
			$user->save();

			throw new LoginException("Incorrect Username Email Password", 1);
 		}

 		// account active?
 		// TO TEST BY CREATING NEW ACCOUNT
 		if ($user->Active != 1)  			// no :-(
			throw new LoginException("Account Not Activated", 3);

        // reset failed login
        $user->FailedLogins = 0;
        $user->LastFailedLogin = NULL;
        $user->save();
        return $user->UserID;
	}

	public static function addNew($data)
	{
		// check if this email already exists in user table
		// if it does, it means it exists as a Phantom User
		// who is now becoming a real user (Yay!)
		$user = User::where('EMail','=',$data['email'])->first();
		$userExists = false;
		$userID = '';
		if ($user==NULL)
		{
			$userID = self::generateUserID();	
		}
		else
		{
			$userID = $user->UserID;
			$userExists = true;
		}
		
		$pwd = $data['password'];		
		$cryptPwd = self::cryptPwd($pwd);
		$activationHash = self::getSimpleHash();

		DB::beginTransaction();

		try 
		{
			$userA = new UserAccess;
			$userA->UserID = $userID;
			$userA->Username = $data['username'];
			$userA->EMail = $data['email'];
			$userA->Pwd = $cryptPwd;
			$userA->ActivationHash = $activationHash;
			$userA->RegistrationIP = $_SERVER['REMOTE_ADDR'];
			$userA->save();

			if (!$userExists)
				$user = new User;
			//else
				// overwrite existing user
				// this needs to be refined
				// what if we are losing valuable lending 
				// info of existing users? 
				// [well only name, email, phone right now]
			$user->UserId = $userID;
			$user->FullName = $data['name'];
			$user->Locality = $data['locality'];
			$user->City = $data['city'];
			$user->State = $data['state'];
			$user->Country = $data['country'];
			$user->EMail = $data['email'];
			$user->PhoneNumber = NULL;
			$user->LocationID = Location::newUserLocationID($data['city'],$data['state'],$data['country']);
			$user->save();

		}
		catch (Exception $e)
		{
			DB::rollback();
			return [false,"DB Error: ".$e->getMessage()];
			//throw $e;
		}				
		DB::commit();

		$emailData = array('name'=>$data['name']);
		$emailData['email'] = $data['email'];
		$emailData['verificationCode'] = $activationHash;

		try
		{
			Mail::send('emails.auth.newUEmailVerify',$emailData, function($message) use ($data)
			{
				$message->to($data['email'], $data['name'])
						->subject('Account Activation');
			});
		}
		catch (Exception $e)
		{
			// TODO: DELETE RECORD FROM DB!!!
			if (!userExists)
				$user->delete();
			$userA->delete();
			var_dump('mail not sent '.$e->getMessage());
			return [false,"Unable to send activation email. Please try later."];
		}
		return [true,""];
	}

	// a phantom user is not yet a real user of the system
	// it gets created via direct lending by a user
	// record is entered only in user, not in user access
	public static function addNewPhantomUser($name, $email=null, $phone=null)
	{
		$emailGiven = false;
		$phoneGiven = false;
		$matchedBy = "";

		// basic parameter format validation
		$validationRules = array(
			'Name' => "required|min:2",
            'Email' => "email",
            'Phone' => 'regex:/^[0-9]+$/'
        );
        $data = array('Phone' => $phone,
        				'Email' => $email,
        				'Name' => $name );
        $validator = Validator::make($data, $validationRules);
        if ($validator->fails()) 
            return array('UserID' => false, 
				'msg' => $validator->messages());

		// either borrower email or phone given?
		if ((strlen($email)==0)&&(strlen($phone)==0))
			return array('UserID' => false, 
				'msg' => "Insufficient Borrower Details. Either email or phone number required.");

		// check if user exists
		if (strlen($email)>0)
			$emailGiven = true;
		if (strlen($phone)>0)
			$phoneGiven = true;

		$user=NULL;
		if ($emailGiven)
		{
			$user = User::where('EMail','=',$email)->first();
			if ($user != NULL)
				$matchedBy = "email";
		}
		else
		{
			if ($phoneGiven)
			{
				$user = User::where('PhoneNumber','=',$phone)->first();
				if ($user != NULL)
					$matchedBy = "phone";
			}
			else
				return array('UserID' => false, 
				'msg' => "Insufficient Borrower Details. Either email or phone number required.");
		}

		if ($user==NULL)
		{
			// add new phantom user
			$userID = self::generateUserID();
			$user = new User;
			$user->UserId = $userID;
			$user->FullName = $name;
			if ($emailGiven)
				$user->EMail = $email;
			else
				$user->EMail = NULL;
			if ($phoneGiven)
				$user->PhoneNumber = $phone;
			else
				$user->PhoneNumber = NULL;
			$result = $user->save();

			if ($result)	// saved successfully
				return array('UserID' => $userID, 
				'msg' => "New User",
				'isPhantom' => true);
		}
		else // some user already exists. overwrite and send existing userid
		{
			$isPhantom = true;
			// check if user is real user by searching in UserAccess
			$userA = self::where('UserID','=',$user->UserID)->first();
			if ($userA != NULL)
				$isPhantom = false;

			if ($isPhantom)	// change these details only for phantom users, not real users
			{
				$user->FullName = $name;
				if ($emailGiven)
					$user->EMail = $email;
			}
			if ($phoneGiven)
				$user->PhoneNumber = $phone;
			$result = $user->save();

			return array('UserID' => $user->UserID, 
				'msg' => "User Existed. Matched by ".$matchedBy,
				'isPhantom' => $isPhantom);
		}
	}

	public static function verifyUserEmail($email, $activationHash)
	{
		$user = NULL;
		$user = self::where('EMail','=',$email)
						->first();

		if ($user == NULL)
			return false;
		if ($user->Active == 1)
			return true;
		if ($user->ActivationHash != $activationHash)
			return false;

		$user->Active = 1;
		$user->ActivationHash = NULL;
		$result = $user->save();

		$humanUser = $user->getHumanUser();

		$view = 'emails.welcome';
		$viewData = array('userHumanName'=>$humanUser->FullName);
		$subject = "Welcome to ".Config::get('app.name')."!";
		$to = [];
		$to['email'] = $humanUser->EMail;
		$to['name'] = $humanUser->FullName;
		$fromPersonal = true;

		Postman::mailToUser($view,$viewData,$subject,$to,$fromPersonal);

		$bodyText = 'New User Activated ' . $user->UserID . ' | ' .
					$humanUser->FullName . ' | ' .
					$humanUser->Locality . ' | ' .
					$humanUser->City . ' | ' .
					$humanUser->State . ' | ' .
					$humanUser->Country . ' | ' .
					$humanUser->EMail;
		$subject = 'New ' . Config::get('app.name') . ' User';
		Postman::mailToAdmin($subject,$bodyText);

		return $result;
	}

	// send a pwd reset link to given email id
	// after generating hash and storing in db
	// when user forgets password and wants to reset it
	// 3 RETURNS:
	// [false,'Email Not Found']
	// [false,'Unable To Send Email']
	// [true,'']
	public static function sendResetPwdLink($email)
	{
		$user = self::getUserByEmail($email);
		if ($user == NULL)
			return [false,'Email Not Found'];
		else
		{
			$pwdResetHash = self::getSimpleHash();
			$user->PwdResetHash = $pwdResetHash;
			$user->PwdResetTimestamp = time();
			$user->save();

			// email pwd reset link ***************
			$humanUser = $user->getHumanUser();
			$emailData = array('name'=>$humanUser->FullName);
			$emailData['email'] = $user->EMail;
			$emailData['id'] = $user->UserID;
			$emailData['resetCode'] = $pwdResetHash;
			try
			{
				Mail::send('emails.auth.resetPwd', $emailData, function($message) use ($emailData)
				{
					$message->to($emailData['email'], $emailData['name'])
							->subject('Reset Password');
				});
			}
			catch (Exception $e)
			{
				//echo($e->getMessage());
				return [false,'Unable To Send Email'];
			}
			return [true,''];			
		}
	}

	// verify if given id and resetCode exist and are fresh
	// only then show the reset password form
	// RETURNS:
	// [false,'ID/Code Not Found']
	// [false,'Expired']
	// [true,'']
	public static function verifyPwdResetLink($id,$resetCode)
	{
		$user = self::find($id);
		if ($user == NULL)
			return [false,'ID/Code Not Found'];
		if ($user->PwdResetHash != $resetCode)
			return [false,'ID/Code Not Found'];
		$expiryMinutes = Config::get('auth.reminder')['expire'];
		//echo('\nexp min '.$expiryMinutes);
		$expiryCutOff = time() - ($expiryMinutes*60);
		//echo('\ncutoff '.$expiryCutOff);
		//echo('\nin db '.$user->PwdResetTimestamp);
		if ($user->PwdResetTimestamp < $expiryCutOff)
			return [false,'Expired'];
		else
			return[true,''];
	}

	// reset the pwd
	// RETURNS:
	// whatever verifyPwdResetLink returns + 
	// [false,'Password Not Valid']
	// [true,'']
	public static function resetPwd($id,$resetCode,$newPwd)
	{
		$result = self::verifyPwdResetLink($id,$resetCode);
		if (!$result[0])
			return $result;
		if (strlen($newPwd)<6)
			return [false,'Password Not Valid'];

		$cryptPwd = self::cryptPwd($newPwd);
		$user = self::find($id);
		$user->Pwd = $cryptPwd;
		$user->save();

		return [true,''];
	}
}

?>
