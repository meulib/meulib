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

//Artisan::call('up');
// echo 'maintenance ended';

// -------- APP ROUTES --------------------------

Route::get('test-clickable-link',function()
{
	echo 'sending mail';
	$view = 'emails.testingClickableLinks';
	$viewData = [];
	$subject = "Testing Clickable Links";
	$to = [];
	$to['email'] = 'vani.murarka@gmail.com';
	$to['name'] = 'Vani Murarka';
	$fromPersonal = true;

	Postman::mailToUser($view,$viewData,$subject,$to,$fromPersonal);
	echo 'mail sent';
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

Route::get('b/{mode?}/{location?}/{language?}/{category?}', 
	array('as' => 'browse', 'uses' => 'BookController@showAll'));
Route::get('browse/{mode?}/{location?}/{language?}/{category?}', 
	array('uses' => 'BookController@showAll'));
Route::get('search', 
	array('as' => 'search', 'uses' => 'BookController@search'));

Route::get('book/{id?}', array('as' => 'single-book', 'uses' => 'BookController@showSingle'));

Route::get('my-books', array('as' => 'my-books', 'uses' => 'UserGateway@showUserCollection'));
// Route::get('my-book', array('as' => 'my-single-book', 'uses' => 'BookController@showSingle'));
Route::get('borrowed-books', array('as' => 'borrowed', 'uses' => 'BookController@borrowedBooks'));

Route::post('add-book', 'BookController@addBook');
Route::post('add-book-info', 'BookController@setBookInfo');
Route::post('edit-book', 'BookController@editBook');
Route::post('edit-bookcopy', array('as'=>'edit-bookcopy', 'uses' => 'BookController@editBookCopy'));

Route::post('delete-book-confirmation', 'BookController@serveDeleteBookConfirmation');
Route::post('delete-bookcopy', 'BookController@deleteBookCopy');

// ----- MEMBER BROWSE ------------

Route::get('founding-members', ['as'=>'founders','uses'=>'UserController@foundingMembers']);
Route::get('m/{country?}', 
	array('as' => 'member-browse','uses' => 'LibrarianCounter@whereAreYourBranches'));

// ----- ACCOUNT CREATION ------------

Route::get('become-a-member', 'UserController@signup');

Route::post('account/submit', 'UserController@submitSignup');

Route::get('account/activate/{email?}/{verification_code?}', array(
	'as' => 'activate',
	'uses' => 'UserController@activate'));

Route::post('set-library-settings', 'UserController@setLibrarySettings');
Route::post('set-profile-pic', ['as'=>'set-profile-pic','uses'=>'UserController@setProfilePicture']);

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
Route::post('give-away','TransactionController@giveAway');

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

// Route::get('/delivery-service', array('as' => 'delivery-service', function()
// {
// 	return View::make('delivery-service');
// }));

Route::get('/vision', array('as' => 'vision', function()
{
	return View::make('vision');
}));

Route::get('/contact-admin', function()
{
	return View::make('contactAdmin');
});

Route::post('submit-contact', 'UtilityController@submitContactForm');

// ------- BLOG ROUTES --------------

// Route::get('/blog/{postSlug}', 'BlogGateway@postPage');
// Route::get('/blog', 'BlogGateway@homePage');


// ------- MAINTENANCE --------------

Route::get('showCaptcha', 'UtilityController@showCaptcha');

Route::group(array('before' => 'admin'), function()
{
    Route::get('/admin', function()
    {
        echo 'ok you have access to admin functionality';
    });

    Route::get('/admin/clear-cache', function()
	{
		Cache::flush();
		echo 'cache flushed';
	});

    Route::get('/admin/add-book', 'BookController@getAdminAddBook');
    //Route::post('add-book', 'BookController@addBook');

    Route::get('update-search', 'MaintenanceController@updateSearch');
});



// ------- MASTER CALL --------------

Route::get('/{username}', 
	array('as' => 'user-books', 'uses' => 'UserGateway@showUserCollection'));
Route::get('/', 
	array('as' => 'home', 'uses' => 'HomeGateway@showHome'));
// {
// 	return View::make('home');
// });

/*Route::get('test', function()
{
	$routeCollection = Route::getRoutes();

    foreach ($routeCollection as $route) 
    {
	    $path = $route->getPath();
	    if (substr($path,0,1)=="/")
	        $path = substr($path, 1);
	    $firstPartEnd = strpos($path,'/');
	    if ($firstPartEnd)
	    {
	        $firstPart = substr($path, 0, $firstPartEnd);
	    }
	    else
	    {
	        $firstPart = $path;
	    }
	    echo $firstPart.'<br/>';
	}
});*/