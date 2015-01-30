<?php 

//require_once('MyExceptions.php');

Validator::extend('captcha', function($field, $value, $params)
{
    if (Session::has('captcha'))
    {
        $generatedCaptcha = Session::pull('captcha');
        if (strtoupper($value) == strtoupper($generatedCaptcha))
            return true;
        else
            return false;
    }
    else
        return false;
});

class UserController extends BaseController
{

    public function loginView()
    {
        if (Session::has('loggedInUser'))
            return Redirect::to(URL::to('/'));

        return View::make('login');
    }

    public function login()
    {
        $userNameEmail = Input::get('user_name');
        $pwd = Input::get('user_password');
        $fromURL = Input::get('fromURL');
        try
        {
            $result = UserAccess::login($userNameEmail, $pwd);
            if ($result)
            {
                $loggedInUser = User::find($result);
                Session::put('loggedInUser',$loggedInUser);
                if (strlen($fromURL)>0)
                    return Redirect::to($fromURL);
                else
                {
                    return Redirect::to(URL::to('/'));
                }
            }
            else
                throw new Exception("Something Wrong In Login", 1);               
        }
        catch (LoginException $e)
        {
            return View::make('login', array('result' => [false,$e->getMessage()]));
        }
    }

    public function logout()
    {
        Session::flush();
        return Redirect::to(URL::to('/'));
    }

    public function forgotPwdView()
    {
        return View::make('forgotPwd');
    }

    public function forgotPwd()
    {
        $appName = Config::get('app.name');
        $email = Input::get('email');
        $result = UserAccess::sendResetPwdLink($email);
        if ($result[0])
        {
            $result[1] = "A password reset link has been emailed to you.<br/>Please check your email.";
        }
        else
        {
            if ($result[1] == "Email Not Found")
                $result[1] = "This email address not found in ".$appName.".<br/>Please try again.";
            if ($result[1] == "Unable To Send Email")
                $result[1] = $appName." is having trouble sending emails at present.<br/>Please try later.";
        }
        return View::make('forgotPwd', array('result' => $result));
    }

    public function resetPwdView($id,$resetCode)
    {
        $result = UserAccess::verifyPwdResetLink($id,$resetCode);
        if ($result[0])
            return View::make('resetPwd', array('id'=>$id,'resetCode'=>$resetCode));
        else
        {
            if ($result[1]=="Expired")
            {
                $result = [false,'The password reset link has expired.<br/>Please generate a new one using the form below.'];
                return View::make('forgotPwd', array('result' => $result));
            }
            else
                App::abort(404);
        }
            
    }

    public function resetPwd()
    {
        $data = Input::all();

        $rules = array(
            'password' => 'required|confirmed|min:6',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) 
        {
            return Redirect::back()->withErrors($validator);
        }

        $result = UserAccess::resetPwd($data['id'],$data['resetCode'],$data['password']);
        if ($result[0])
            return View::make('login', array('result' => 
                [false,"Password Reset successful.<br/>You can now 
                login with your new password."]));
        else
            return Redirect::back()->withErrors(['msg', 'Password Reset not successful.<br/>The system is encountering some problems.']);
            
    }

    public function signup()
    {
        return View::make('signup');
    }

    public function activate($id = null, $verification_code = null)
    {
        //$id = Input::get('id');
        //$aHash = Input::get('verification_code');
        $result = UserAccess::verifyUserEmail($id,$verification_code);
        if ($result)
            return View::make('accountActivated');
        else
        {
            $bodyText = "Account Activation Failed: " . $id . " | " . $verification_code;
            $body = array('body'=>$bodyText);

            Mail::send(array('text' => 'emails.raw'), $body, function($message)
            {
                $message->to(Config::get('mail.admin'))
                        ->subject('New ' . Config::get('app.name') . ' Ac Failed');
            });
            return View::make('accountActivationFail');
        }
    }

    public function submitSignup()
    {

        $data = Input::all();

        $rules = array(
            'email' => 'required|email|confirmed|max:100|unique:user_access,EMail',
            'name' => 'required',
            'locality' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'username' => 'required|alpha_num|between:2,64|unique:user_access,Username',
            'password' => 'required|confirmed|min:6',
            'captcha' => 'captcha'
        );
        
        $messages = array(
            'integer' => 'The :attribute must be number digits only.',
            'captcha' => 'Entered :attribute characters do not match the generated image.'
        );

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) 
        {
            return Redirect::to(URL::previous())->withErrors($validator);
        }

        $result = UserAccess::addNew($data);
        if ($result[0])
            return View::make('signupSubmit');
        else
            return Redirect::to(URL::previous())->withErrors([$result[1]]);;
    }

    // display founding members
    // i.e. member who opened lib in xyz location, 
    // member who first shared 1000 books and so on
    public function foundingMembers()
    {
        $appName = Config::get('app.name');
        $founders = Founder::orderBy('When', 'desc')->get();
        $founders->each(function($founder) use ($appName)
        {
            $founder->ClaimToFame = str_replace('appName', $appName, $founder->ClaimToFame);
        });
        return View::make('founders', array('founders'=>$founders));
    }
}
?>