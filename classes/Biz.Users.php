<?php
require_once('DB.Connection.php');
require_once("Exceptions.php");
if (!defined('__ROOT__')) define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/config.php'); 

class Users
{
	private $dbconn;
	private $loggedIn = false;
	private $name = "";
	private $UserID = "";
	private $UserRealName = "";
	private $ActivationHash = "";
	private $errors = array();

	function __construct() 
	{
       $this->dbconn = new DBConn();
	}

	function __destruct()
	{
		$this->dbconn = null;
    }

	public function getUserID()
	{
		return $this->UserID;
	}

	public function getActivationHash()
	{
		return $this->ActivationHash;
	}

	public function getUserRealName()
	{
		return $this->UserRealName;
	}

	public function chkUniqueUsername($username)
	{
		$ok = false;
		$sql = "SELECT count(*) FROM  ".TBL_PREFIX."user_access"
				. " WHERE Username = :username";
		$result = $this->dbconn->selectOneVal($sql,array('username' => $username),true);
		if ($result==0)
			$ok = true;
		return $ok;
	}

	public function chkUniqueEmail($email)
	{
		$ok = false;
		$sql = "SELECT count(*) FROM  ".TBL_PREFIX."user_access"
				. " WHERE EMail = :email";
		$result = $this->dbconn->selectOneVal($sql,array('email'=>$email),true);
		if ($result==0)
			$ok = true;
		return $ok;
	}

	private function generateUserID()
	{
		$found = false;
		$an = "";
		while (!$found)
		{
			$a = chr(rand(1,26)+64).chr(rand(1,26)+64).chr(rand(1,26)+64).chr(rand(1,26)+64);
			$n = mt_rand()*2;
			$an = $a.$n;
			$sql = "SELECT count(*) FROM  ".TBL_PREFIX."user_access"
				. " WHERE UserID = :userid";
			$result = $this->dbconn->selectOneVal($sql,array('userid'=>$an),false);
			if ($result==0)
				$found = true;
		}
		$this->dbconn->close();
		return $an;
	}

	private function findUserByUsername($username)
	{
		$sql = "select * from ".TBL_PREFIX."user_access where Username = :username";
		$result = $this->dbconn->selectOneRow($sql, array ('username' => $username), true);
		return $result;
	}

	private function findUserByEmail($email)
	{
		$sql = "select * from ".TBL_PREFIX."user_access where EMail = :email";
		$result = $this->dbconn->selectOneRow($sql, array ('email' => $email), true);
		return $result;
	}

	public function addNew($userDetails)
	{
		$this->UserID = self::generateUserID();
		//echo $UserID;
		$pwd = crypt($userDetails['pwd'],'$5$'.substr(md5(uniqid(rand(),true)),0,13));
		$this->ActivationHash = sha1(uniqid(mt_rand(), true));

		$sqlInsertUserAccess = "insert into ".TBL_PREFIX."user_access (UserID, Username, EMail, Pwd, "
			. "ActivationHash,RegistrationIP, RegistrationDateTime) "
			. "values (:userid, :username, :email, :pwd, :activationHash, :registrationIP, now())";
		$userAccess = array('userid'=>$this->UserID,
			'username'=>$userDetails['username'],
			'email' => $userDetails['email'],
			'pwd'=>$pwd,
			'activationHash'=>$this->ActivationHash,
			'registrationIP'=>$_SERVER['REMOTE_ADDR']);
		$sqlInsertUser = "insert into ".TBL_PREFIX."users (UserId, FullName, Address, Locality, City, State, "
			. "EMail, PhoneNumber) values (:userid, :name, :address, :locality," .
			":city, :state, :email, :phone)";
		$user = array('userid'=>$this->UserID,
			'name'=>$userDetails['name'],
			'address'=>$userDetails['address'],
			'locality'=>$userDetails['locality'],
			'city'=>$userDetails['city'],
			'state'=>$userDetails['state'],
			'email'=>$userDetails['email'],
			'phone'=>$userDetails['phone']);
		
		try
		{
			$this->dbconn->transactionBegin();
			$this->dbconn->executeInTran($sqlInsertUserAccess,$userAccess);
			$this->dbconn->executeInTran($sqlInsertUser,$user);
			$this->dbconn->transactionCommit();
			return true;
		} catch (PDOException $e)
		{
			$this->dbconn->transactionRollback();
			// echo 'TRANSACTION ERROR: ' . $e->getMessage();
			return false;
		}
	}

	public function verifyUserEmail($userid, $activationHash)
	{
		$sql = "update ".TBL_PREFIX."user_access set Active = 1, ActivationHash = NULL " . 
			"WHERE UserID = :userid AND ActivationHash = :activationHash";
		$user = array('userid'=>$userid,
			'activationHash' => $activationHash);
		$affected = $this->dbconn->execute($sql, $user, true);
		if ($affected > 0)
			return true;
		else
			return false;
	}

	public function login($userNameEmail, $pwd)
	{
		// user can login with username or email
		if (!filter_var($userNameEmail, FILTER_VALIDATE_EMAIL)) // given value not email
		{
            $userRow = $this->findUserByUsername(trim($userNameEmail));
        }
        else // email seems to be given
        {
        	$userRow = $this->findUserByEmail(trim($userNameEmail));	
        }

        // no row found
        if (!isset($userRow['UserID']))
        {
			$this->errors[] = MESSAGE_LOGIN_FAILED;
			return false;
		}

		// 3 earlier failed login attempts. ask user to wait for 30 seconds
		// to prevent automated tries
		if (($userRow['FailedLogins'] >= 3) && ($userRow['LastFailedLogin'] > (time() - 30)))
		{
            $this->errors[] = MESSAGE_PASSWORD_WRONG_3_TIMES;
            return false;
        }

 		// verify password
 		if (!(crypt($pwd,$userRow['Pwd']) == $userRow['Pwd']))
 		{
 			// pwd failed
			$this->errors[] = MESSAGE_LOGIN_FAILED;
			// increment failed attempts
			$this->failedLogin($userRow['UserID']);
			return false;
 		}

 		// account active?
 		if ($userRow['Active'] != 1)
 		{
 			// no :-(
        	$this->errors[] = MESSAGE_ACCOUNT_NOT_ACTIVATED;
        	return false;
        }

        // SUCCESS!

        // reset failed attempts as this one is good
        $this->resetFailedLogin($userRow['UserID']);
        $this->UserID = $userRow['UserID'];
        // retrive user's human name
        $this->UserRealName = $this->retrieveUserRealName();

		return true;
	}

	private function retrieveUserRealName()
	{
		$sql = "select FullName from ".TBL_PREFIX."users where UserID = :userid";
		$result = $this->dbconn->selectOneVal($sql, array('userid'=>$this->UserID),true);
		return $result;
	}

	private function failedLogin($userid)
	{
		$sql = "UPDATE ".TBL_PREFIX."user_access SET FailedLogins = FailedLogins+1, "
			. "LastFailedLogin = :failedLoginTime WHERE UserID = :userid";
        $this->dbconn->execute($sql,array('userid' => $userid, 
        	':failedLoginTime' => time()),true);
	}

	private function resetFailedLogin($userid)
	{
		$sql = "UPDATE ".TBL_PREFIX."user_access SET FailedLogins = 0, "
			. "LastFailedLogin = NULL WHERE UserID = :userid";
        $this->dbconn->execute($sql,array('userid' => $userid));
	}
}