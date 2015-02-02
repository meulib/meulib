<?php 

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

class UtilityController extends BaseController
{

    public function showCaptcha()
    {

        /**
         * This file generates a captcha string, writes it into the $_SESSION['captcha']
         * and renders a fresh captcha graphic file to the browser.
         *
         * In the views you can use this by saying:
         * <img src="tools/showCaptcha.php" />
         *
         * Check if the typed captcha is correct by saying:
         * if ($_POST["captcha"] == $_SESSION['captcha']) { ... } else { ... }
         */

        // check if php gd extension is loaded
        if (!extension_loaded('gd')) {
            die("It looks like GD is not installed");
        }

        // target captcha string length
        $iCaptchaLength = 4;

        // following letters are excluded from captcha: I, O, Q, S, 0, 1, 5
        $str_choice = 'ABCDEFGHJKLMNPRTUVWXYZ2346789';
        $str_captcha = '';
        // create target captcha with letters comming from $str_choice
        for ($i=0; $i < $iCaptchaLength; $i++) {
            do {
                $ipos = rand(0, strlen($str_choice) - 1);
            // checks that each letter is used only once
            } while (stripos($str_captcha, $str_choice[$ipos]) !== false);

            $str_captcha .= $str_choice[$ipos];
        }

        // write the captcha into a SESSION variable
        //$_SESSION['captcha'] = $str_captcha;
        Session::put('captcha',$str_captcha);

        // begin to create the image with PHP's GD tools
        $im = imagecreatetruecolor(150, 70);

        $bg = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $bg);

        // create background with 1000 short lines
        for ($i=0;$i<1000;$i++) {
            $lines = imagecolorallocate($im, rand(200, 220), rand(200, 220), rand(200, 220));
            $start_x = rand(0,150);
            $start_y = rand(0,70);
            $end_x = $start_x + rand(0,5);
            $end_y = $start_y + rand(0,5);
            imageline($im, $start_x, $start_y, $end_x, $end_y, $lines);
        }

        // create letters. for more info on how this works, please
        // @see php.net/manual/en/function.imagefttext.php
        $fontPath = public_path() . '/fonts/times_new_yorker.ttf';
        //return $fontPath; 
        for ($i=0; $i < $iCaptchaLength; $i++) {
            $text_color = imagecolorallocate($im, rand(0, 100), rand(10, 100), rand(0, 100));
            // font-path relative to this file
            imagefttext($im, 35, rand(-10, 10), 20 + ($i * 30) + rand(-5, +5), 35 + rand(10, 30), $text_color, $fontPath, $str_captcha[$i]);
        }

        // send http-header to prevent image caching (so we always see a fresh captcha image)
        header('Content-type: image/png');
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, proxy-revalidate');

        // send image to browser and destroy image from php "cache"
        imagepng($im);
        imagedestroy($im);

    }

    public function submitContactForm()
    {
        $data = Input::all();
        $rules = array(
            'email' => 'required|email',
            'message' => 'required',
            'captcha' => 'captcha'
        );
        $messages = array(
            'captcha' => 'Entered :attribute characters do not match the generated image.'
        );
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) 
        {
            return Redirect::to(URL::previous())->withErrors($validator);
        }

        $body = 'Name: ' . $data['name'] . "\n" .
                'Email: ' . $data['email'] . "\n" .
                'Msg: ' . $data['message'];
        // $body = array('body'=>$body);
        $subject = Config::get('app.name') . ' Contact Form';

        try
        {
            // Mail::send(array('text' => 'emails.raw'), $body, function($message)
            // {
            //     $message->to(Config::get('mail.admin'))
            //             ->subject(Config::get('app.name') . ' Contact Form');
            // });
            AppMailer::MainToAdmin($subject,$body);
        }
        catch (Exception $e)
        {
            //var_dump('seems to have failed'.$e);
            return Redirect::to(URL::previous())->withErrors('Unable to send this message right now. Please try later.');
        }
        //var_dump('seems to be success');
        return Redirect::to(URL::previous())->with('result', [true,'']);
        
    }
}
?>