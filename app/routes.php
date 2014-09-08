<?php

Route::get('/', function()
{
	return View::Make('dashboard');
});

Route::group(array('prefix' => 'api'), function() {

	Route::get('/', function() {
		$url = URL::to('/').'/api';
		return Response::json(array(
				'status' => $url,
				'words' => $url.'/words',
				'word' => $url.'/words/{id}',
				'randomwords' => $url.'/randomwords',
				'moderate_words' => $url.'/moderate/words',
				'moderate_word' => $url.'/moderate/words/{id}',
				'user_words' => $url.'/user/{id}/words',
				'categories' => $url.'/categories',
				'category' => $url.'/category/{id}',
				'settings' => $url.'/settings'
			), 200);
	});

	Route::post('/', array('before' => 'auth', function() {
		return Response::json(['Authenticated'], 200);
	}));

	Route::resource('words', 'WordCardsController');
	Route::get('randomwords', 'WordCardsController@randomwords');

	/* Moderation */

	Route::get('moderate/words', 'MwordsController@show_all');
	Route::get('moderate/words/{word_id}', 'MwordsController@show');
	Route::put('moderate/words/{word_id}', 'MwordsController@update');
	Route::delete('moderate/words/{word_id}', 'MwordsController@decline');
	Route::delete('moderate/{word_id}/remove', 'MwordsController@remove_word');
	Route::post('moderate/words/{word_id}/changestatus', 'MwordsController@change_status');
	Route::post('moderate/words/{word_id}/acceptpush', 'MwordsController@acceptPush');

	Route::group(['before' => 'auth.admin'], function() {

		/* Category */

		Route::resource('categories', 'CategoriesController');

		Route::get('/categories/{category_id}/words', 'CategoriesController@show_words');

		Route::get('users', 'UsersController@index');

	});

	Route::post('/client/categories', ['before' => 'auth', 'uses' => 'CategoriesController@index']);
// TODO
	// Route::resource('users', 'UsersController');

	//update
	// destroy
	Route::post('users', 'UsersController@store');

	Route::get('user/{user_id}/words', 'MwordsController@show_words');
	Route::post('users/adminauth', 'UsersController@adminauth');

	Route::group(['prefix' => 'user', 'before' => 'auth'], function() {

		Route::get('{user_id}/firstword', 'UsersController@firstword');

		// Hardcoded
//mysql://b4ccce6110afe6:35364b49@eu-cdbr-west-01.cleardb.com/heroku_928cffd4c5b526d?reconnect=true
		Route::post('sentwords', 'WordCardsController@sentwords');

		Route::post('purchase', 'UsersController@purchase');

		Route::post('restore', 'UsersController@restore');

		Route::post('completesurvey', 'UsersController@completesurvey');

		// =========

		Route::post('{user_id}/addword', 'MwordsController@add_word');
		// Route::post('{user_id}/restore', 'UserController@restore');

		Route::get('{user_id}/getbonus', 'UsersController@getbonus');

		Route::post('{user_id}', 'UsersController@show');
		Route::post('{user_id}/edit', 'UsersController@addDevice');
		Route::post('{user_id}/addlife', 'UsersController@addlife');

		Route::post('{user_id}/subscribe', 'UsersController@subscribe');
		Route::post('{user_id}/unsubscribe', 'UsersController@unsubscribe');
		Route::post('{user_id}/subscriptions', 'UsersController@subscriptions');

		Route::post('{user_id}/teststart', 'TestsController@start');
		Route::post('{user_id}/testend', 'TestsController@result');
	});



	/* Settings */

	Route::get('settings', 'SettingsController@index');
	Route::post('settings', 'SettingsController@update');

});

Route::match(array('GET', 'POST'), '/oauth', 'OauthController@login');

Route::any('{slug}', function() {
	return Redirect::to('/');
});
