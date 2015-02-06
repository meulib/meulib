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

// Artisan::call('down');

// Artisan::call('migrate', [
// 	        '--force' => true
// 	        ]);
// echo 'migration done';

// Artisan::call('up');
// echo 'maintenance ended';

// -------- APP ROUTES --------------------------

Route::get('/', function()
{
	return View::make('home');
});

Route::get('generic-error', function()
{
	return View::make('genericError');
});

Route::post('queue/receive', function()
{
    return Queue::marshal();
});

Route::get('signup-or-login',function()
{
	if(Input::get('btnMember')) {
    	return Redirect::to('become-a-member');
	} elseif(Input::get('btnLogin')) {
    	return Redirect::to('login');
	}
});

// ----- BOOK ROUTES --------------

Route::get('browse/{location?}/{language?}/{category?}', array('as' => 'browse', 'uses' => 'BookController@showAll'));

Route::get('book/{id?}', array('as' => 'single-book', 'uses' => 'BookController@showSingle'));

Route::get('my-books', array('as' => 'my-books', 'uses' => 'BookController@myBooks'));
// Route::get('my-book', array('as' => 'my-single-book', 'uses' => 'BookController@showSingle'));
Route::get('borrowed-books', array('as' => 'borrowed', 'uses' => 'BookController@borrowedBooks'));

Route::post('add-book', 'BookController@addBook');
Route::post('add-book-info', 'BookController@setBookInfo');

Route::post('delete-book-confirmation', 'BookController@serveDeleteBookConfirmation');
Route::post('delete-bookcopy', 'BookController@deleteBookCopy');

// ----- ACCOUNT CREATION ------------

Route::get('become-a-member', 'UserController@signup');

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
Route::post('lend', 'TransactionController@lend');

Route::post('return-form', 'TransactionController@returnForm');
Route::post('accept-return', 'TransactionController@acceptReturn');

Route::get('/messages/{tranID?}', array('as' => 'messages', 'uses' => 'TransactionController@messages'));

// ------- INFO PAGES --------------

Route::get('/membership-rules', array('as' => 'membership-rules', function()
{
	return View::make('membershipRules');
}));

Route::get('/how-it-works', array('as' => 'how-it-works', function()
{
	return View::make('howorks');
}));

Route::get('/how-it-works-borrower', function()
{
	return View::make('howorksborrower');
});

Route::get('/how-it-works-owner', function()
{
	return View::make('howorksowner');
});

Route::get('/faq', array('as' => 'faq', function()
{
	return View::make('faq');
}));

Route::get('/vision', array('as' => 'vision', function()
{
	return View::make('vision');
}));

Route::get('/contact-admin', function()
{
	return View::make('contactAdmin');
});

Route::get('founding-members', 'UserController@foundingMembers');

Route::post('submit-contact', 'UtilityController@submitContactForm');
