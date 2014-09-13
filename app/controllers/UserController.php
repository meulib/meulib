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

    public function login()
    {
        $userNameEmail = Input::get('user_name');
        $pwd = Input::get('user_password');
        try
        {
            $result = UserAccess::login($userNameEmail, $pwd);
            if ($result)
            {
                $loggedInUser = User::find($result);
                Session::put('loggedInUser',$loggedInUser);
                return Redirect::to(URL::previous());
            }
            else
                throw new Exception("Something Wrong In Login", 1);               
        }
        catch (LoginException $e)
        {
            echo $e->getMessage();
        }
    }

    public function logout()
    {
        Session::flush();
        return Redirect::to(URL::previous());
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
            return var_dump($result);
    }

    public function submitSignup()
    {

        $data = Input::all();

        $rules = array(
            'email' => 'required|email|confirmed|max:100|unique:user_access,EMail',
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'phone' => 'integer',
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
        if ($result)
            return View::make('signupSubmit');
        else
            return "Some error occurred";
    }
}
?>