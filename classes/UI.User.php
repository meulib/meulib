<?php 
require_once("Biz.Users.php");
require_once("Exceptions.php");

class UIUser
{
	private $user;
	private $UserID = "";
	private $LoggedIn = false;

    public function __construct()
    {
        // create/read session
        session_start();

        if (isset($_POST["login"])) 
        {
			$this->loginViaForm($_POST['user_name'], $_POST['user_password']);
        }
    }

    private function loginViaForm($username, $pwd)
    {
    	$this->user = new Users();
    	$result = $this->user->login($username, $pwd);
    	if ($result)
    	{
    		$_SESSION['UserID'] = $this->user->getUserID();
    		$_SESSION['UserRealName'] = $this->user->getUserRealName();
            $_SESSION['UserLoggedIn'] = 1;

            $this->UserID = $this->user->getUserID();
            $this->UserRealName = $this->user->getUserRealName();
            $this->LoggedIn = true;
    	}
    	else
    	{
    		$this->LoggedIn = false;
    	}
    }


}

?>