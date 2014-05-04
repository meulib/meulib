<?php
require_once("languages/lang.en.php");
require_once("ui_includes/URLS.php");
require_once("classes/Biz.Users.php");
require_once('libraries/PHPMailer.php');

// TO DO - move this class to a better place
class Registration
{
    // @var bool success state of registration
    public  $registration_successful  = false;
    // @var bool success state of verification
    public  $verification_successful  = false;
    // @var array collection of error messages
    public  $errors                   = array();
    // @var array collection of success / neutral messages
    public  $messages                 = array();

    public function __construct()
    {
        session_start();

        // if we have such a POST request, call the registerNewUser() method
        if (isset($_POST["register"])) {
            $this->registerNewUser();
        // if we have such a GET request, call the verifyNewUser() method
        } else if (isset($_GET["id"]) && isset($_GET["verification_code"])) {
            $this->verifyNewUser($_GET["id"], $_GET["verification_code"]);
        }
    }

    // handles the entire registration process. checks all error possibilities, and creates a new user in the database if
    // everything is fine
    private function registerNewUser()
    {
        // we just remove extra space on username and email
		$username=trim($_POST['username']);
		$email=trim($_POST['email']);
		$pwd=$_POST['pwd'];
		$pwd2=$_POST['pwd2'];
        $captcha = $_POST['captcha'];

        $usersClass = new Users();

        // check provided data validity
        // TODO: check for "return true" case early, so put this first
        if (strtolower($captcha) != strtolower($_SESSION['captcha'])) {
            $this->errors[] = MESSAGE_CAPTCHA_WRONG;
        } elseif (empty($username)) {
            $this->errors[] = MESSAGE_USERNAME_EMPTY;
        } elseif (empty($pwd) || empty($pwd2)) {
            $this->errors[] = MESSAGE_PASSWORD_EMPTY;
        } elseif ($pwd !== $pwd2) {
            $this->errors[] = MESSAGE_PASSWORD_BAD_CONFIRM;
        } elseif (strlen($pwd) < 6) {
            $this->errors[] = MESSAGE_PASSWORD_TOO_SHORT;
        } elseif (strlen($username) > 64 || strlen($username) < 2) {
            $this->errors[] = MESSAGE_USERNAME_BAD_LENGTH;
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $username)) {
            $this->errors[] = MESSAGE_USERNAME_INVALID;
        } elseif (empty($email)) {
            $this->errors[] = MESSAGE_EMAIL_EMPTY;
        } elseif (strlen($email) > 100) {
            $this->errors[] = MESSAGE_EMAIL_TOO_LONG;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = MESSAGE_EMAIL_INVALID;

        // finally if all the above checks are ok
        } elseif (!$usersClass->chkUniqueUsername($username)) {
        	$this->errors[] = MESSAGE_USERNAME_EXISTS;
        } elseif (!$usersClass->chkUniqueEmail($email)) {
        	$this->errors[] = MESSAGE_EMAIL_ALREADY_EXISTS;
        }
        else 
        {

                // save new users data
                $success = false;             
                $userDetails = array('email'=>$email,
					'name'=>$_POST['name'],
					'address'=>$_POST['address'],
					'locality'=>$_POST['locality'],
					'city'=>$_POST['city'],
					'state'=>$_POST['state'],
					'phone'=>$_POST['phone'],
					'username'=>$username,
					'pwd'=>$pwd);
				$success = $usersClass->addNew($userDetails);
                //echo "INSERT RESULT " . $success;

                if ($success) {
                    $userID = $usersClass->getUserID();
                    $activationHash = $usersClass->getActivationHash();
                    // send a verification email
                    if ($this->sendVerificationEmail($userID, $email, $activationHash)) { 
                        // when mail has been send successfully
                        $this->messages[] = MESSAGE_VERIFICATION_MAIL_SENT;
                        $this->registration_successful = true;
                    } else {
                        // *** TO BE FIXED
                        // delete this users account immediately, as we could not send a verification email
                        $query_delete_user = $this->db_connection->prepare('DELETE FROM users WHERE user_id=:user_id');
                        $query_delete_user->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                        $query_delete_user->execute();

                        $this->errors[] = MESSAGE_VERIFICATION_MAIL_ERROR;
                    }
                } else {
                    $this->errors[] = MESSAGE_REGISTRATION_FAILED;
                }
        }
        
    }

    // sends an email to the provided email address
    // @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
    public function sendVerificationEmail($user_id, $user_email, $user_activation_hash)
    {

        // *** THIS FUNCTION NEEDS TO BE CHECKED
        $mail = new PHPMailer;

        // please look into the config/config.php for much more info on how to use this!
        // use SMTP or use mail()
        if (EMAIL_USE_SMTP) {
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors
            //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            // Enable SMTP authentication
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;
            // Enable encryption, usually SSL/TLS
            if (defined(EMAIL_SMTP_ENCRYPTION)) {
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
            }
            // Specify host server
            $mail->Host = EMAIL_SMTP_HOST;
            $mail->Username = EMAIL_SMTP_USERNAME;
            $mail->Password = EMAIL_SMTP_PASSWORD;
            $mail->Port = EMAIL_SMTP_PORT;
        } else {
            $mail->IsMail();
        }

        $mail->From = ADMIN_EMAIL_FROM;
        $mail->FromName = ADMIN_EMAIL_FROM_NAME;
        $mail->AddAddress($user_email);
        $mail->Subject = EMAIL_VERIFICATION_SUBJECT;

        $link = EMAIL_URL_ROOT.EMAIL_VERIFICATION_URL.'?id='.urlencode($user_id).'&verification_code='.urlencode($user_activation_hash);

        // the link to your register.php, please set this value in config/email_verification.php
        $mail->Body = EMAIL_VERIFICATION_CONTENT.' '.$link;

        if(!$mail->Send()) {
            $this->errors[] = MESSAGE_VERIFICATION_MAIL_NOT_SENT . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
    }

    // checks the id/verification code combination and set the user's activation status to true (=1) in the database
    public function verifyNewUser($userid, $activationHash)
    {
        $usersClass = new Users();
        $result = $usersClass->verifyUserEmail($userid, $activationHash);
        if ($result) 
        {
            $this->verification_successful = true;
            $this->messages[] = MESSAGE_REGISTRATION_ACTIVATION_SUCCESSFUL;
        } else {
            $this->errors[] = MESSAGE_REGISTRATION_ACTIVATION_NOT_SUCCESSFUL;
        }
    }
}

$registration = new Registration();

?>
<html>
<body>
<?php 
//echo $_SERVER['REMOTE_ADDR'];
include 'ui_includes/signup.php';
?>

