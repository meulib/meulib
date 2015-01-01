<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Artisan::call('migrate', [
// 	        '--force' => true
// 	        ]);
// echo 'migration done';

//Artisan::call('down');

//Artisan::call('up');
//echo 'maintenance ended';

// -------- APP ROUTES --------------------------

Route::get('/', function()
{
	return View::make('home');
});

Route::get('generic-error', function()
{
	return View::make('genericError');
});

// ----- BOOK ROUTES --------------

Route::get('browse/{location?}/{language?}', 'BookController@showAll');

Route::get('book/{id?}', 'BookController@showSingle');

Route::post('addbook', 'BookController@addBook');

Route::get('my-books', 'BookController@myBooks');

Route::get('borrowed-books', 'BookController@borrowedBooks');

// ----- ACCOUNT CREATION ------------

Route::get('account/create', 'UserController@signup');

Route::post('account/submit', 'UserController@submitSignup');

Route::get('account/activate/{id?}/{verification_code?}', array(
	'as' => 'activate',
	'uses' => 'UserController@activate'));

Route::get('showCaptcha', 'UtilityController@showCaptcha');

// ------ LOGIN LOGOUT ---------------

Route::get('login', array('as' => 'login', 
	'uses' => 'UserController@loginView'));

Route::post('login', 'UserController@login');

Route::get('logout', 'UserController@logout');

Route::get('forgot-password', 'UserController@forgotPwdView');

Route::post('forgot-password', 'UserController@forgotPwd');

Route::get('reset-password/{id}/{resetCode}', 'UserController@resetPwdView');

Route::post('reset-password', 'UserController@resetPwd');

// ------- TRANSACTIONS --------------

Route::post('request', 'TransactionController@request');

Route::post('reply', 'TransactionController@reply');

Route::post('pending-requests', 'TransactionController@pendingRequests');

Route::post('return-form', 'TransactionController@returnForm');

Route::post('lend', 'TransactionController@lend');

Route::post('accept-return', 'TransactionController@acceptReturn');

Route::get('/messages/{tranID?}', 'TransactionController@messages');

// ------- INFO PAGES --------------

Route::get('/how-it-works', function()
{
	return View::make('howorks');
});

Route::get('/how-it-works-borrower', function()
{
	return View::make('howorksborrower');
});

Route::get('/how-it-works-owner', function()
{
	return View::make('howorksowner');
});

Route::get('/faq', function()
{
	return View::make('faq');
});

Route::get('/vision', function()
{
	return View::make('vision');
});

Route::get('/contact-admin', function()
{
	return View::make('contactAdmin');
});

Route::post('submit-contact', 'UtilityController@submitContactForm');

// -------- CALL ARTISAN MIGRATION --------------

/*Route::get('/xxx/{key?}',  array('as' => 'install', function($key = null)
{
    if($key == "xxx")
    {
	    try 
	    {
	      
	      echo '<br>init with app tables migrations...';
	      Artisan::call('migrate', [
//	        '--path'     => "app/database/migrations",
	        '--force' => true
	        ]);
	      echo '<br>done with app tables migrations';
	      
	    } 
	    catch (Exception $e) 
	    {
	      Response::make($e->getMessage(), 500);
	    }
	}
	else
	{
    	App::abort(404);
	}
}
));*/