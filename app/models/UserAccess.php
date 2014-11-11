<?php

//require_once(MyExceptions.php);

//use Illuminate\Auth\UserTrait;
//use Illuminate\Auth\UserInterface;
//use Illuminate\Auth\Reminders\RemindableTrait;
//use Illuminate\Auth\Reminders\RemindableInterface;

//class User extends Eloquent implements UserInterface, RemindableInterface {

//	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
//	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
//	protected $hidden = array('password', 'remember_token');

//}

class UserAccess extends Eloquent {

	protected $table = 'user_access';
	public $incrementing = false;
	protected $primaryKey = 'UserID';

	public static function Login($userNameEmail, $pwd)
	{
		$user = NULL;

		// user can login with username or email
		if (filter_var($userNameEmail, FILTER_VALIDATE_EMAIL)) // given value is email
			$user = self::where('EMail','=',$userNameEmail)->first();
		else
			$user = self::where('Username','=',$userNameEmail)->first();

		if ($user == NULL)
			throw new LoginException("Incorrect Username Email Password", 1);

		// 3 earlier failed login attempts. ask user to wait for 2 minutes
		// to prevent automated tries
		if (($user->FailedLogins >= 3) && ($user->LastFailedLogin > (time() - (60*2))))
            throw new LoginException("Too Many Failed Recent Attempts", 2);

        // verify password
 		if (!(crypt($pwd,$user->Pwd) == $user->Pwd))
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

	public static function addNew($data)
	{
		$userID = self::generateUserID();
		$pwd = $data['password'];
		$cryptPwd = crypt($pwd,'$5$'.substr(md5(uniqid(rand(),true)),0,13));
		$activationHash = sha1(uniqid(mt_rand(), true));

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

			$user = new User;
			$user->UserId = $userID;
			$user->FullName = $data['name'];
			//$user->Address = $data['address'];
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
			throw $e;
		}				
		DB::commit();

		$emailData = array('name'=>$data['name']);
		$emailData['id'] = $userID;
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
			return false;
		}
		return true;
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

	public static function verifyUserEmail($userid, $activationHash)
	{
		$user = NULL;
		$user = self::where('UserID','=',$userid)
						->where('ActivationHash','=',$activationHash)
						->first();

		if ($user == NULL)
			return false;
		if ($user->Active == 1)
			return true;

		$user->Active = 1;
		$user->ActivationHash = NULL;
		$result = $user->save();

		$body = array('body'=>'New User Activated ' . $userid);

		Mail::send(array('text' => 'emails.raw'), $body, function($message)
		{
			$message->to(Config::get('mail.admin'))
					->subject('New ' . Config::get('app.name') . ' User');
		});

		return $result;
	}
}

?>
