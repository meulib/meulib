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

Route::get('/', function()
{
	return View::make('home');
});

// ----- BOOK ROUTES --------------

Route::get('browse/{category?}', 'BookController@showAll');

Route::get('book/{id?}', 'BookController@showSingle');

Route::post('addbook', 'BookController@addBook');

// ----- ACCOUNT CREATION ------------

Route::get('account/create', 'UserController@signup');

Route::post('account/submit', 'UserController@submitSignup');

Route::get('account/activate/{id?}/{verification_code?}', array(
	'as' => 'activate',
	'uses' => 'UserController@activate'));

Route::get('showCaptcha', 'UtilityController@showCaptcha');

// ------ LOGIN LOGOUT ---------------

Route::post('login', 'UserController@login');

Route::get('logout', 'UserController@logout');

// ------- TRANSACTIONS --------------

Route::post('request', 'TransactionController@request');

Route::post('reply', 'TransactionController@reply');

Route::post('pendingRequests', 'TransactionController@pendingRequests');

Route::post('returnForm', 'TransactionController@returnForm');

Route::post('lend', 'TransactionController@lend');

Route::post('acceptReturn', 'TransactionController@acceptReturn');

Route::get('/messages/{tranID?}', 'TransactionController@messages');

// -------- CALL ARTISAN --------------

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