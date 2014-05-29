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
	if (Auth::check())
	{
		// The user is logged in
		return View::make('editor');
	}
	else
	{
		return View::make('landing');
	}
});

// Routes that only respond to authed requests
Route::any('ajax', array('before' => 'auth', function()
{
	// TODO: (legacy) this should be split up into smaller routes instead of a wrapper
}));

/**
 * Facebook
 */
Route::get('login/fb', function() {
	Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
	$facebook = new Facebook(Config::get('facebook'));
	$params = array(
		'redirect_uri' => url('/login/fb/callback'),
		'scope' => 'email',
	);
	return Redirect::away($facebook->getLoginUrl($params));
});
Route::get('login/fb/callback', function() {
	$code = Input::get('code');
	if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');

	Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
	$facebook = new Facebook(Config::get('facebook'));
	$uid = $facebook->getUser();

	if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');

	$me = $facebook->api('/me');

	$profile = Profile::whereUid($uid)->first();
	if (empty($profile)) {

		$user = new User;
		$user->name = $me['first_name'].' '.$me['last_name'];
		$user->email = $me['email'];
		$user->photo = 'https://graph.facebook.com/'.$me['username'].'/picture?type=large';

		$user->save();

		$profile = new Profile();
		$profile->uid = $uid;
		$profile->username = $me['username'];
		$profile = $user->profiles()->save($profile);
	}

	$profile->access_token = $facebook->getAccessToken();
	$profile->save();

	$user = $profile->user;

	Auth::login($user);

	return Redirect::to('/')->with('message', 'Logged in with Facebook');
});
