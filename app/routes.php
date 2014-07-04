<?php

Route::get('/', function()
{
	return View::Make('dashboard');
});

Route::get('categories/{category}', function() {
	return Redirect::to('/');
});

Route::get('category/{category}/words', function() {
	return Redirect::to('/');
});

Route::get('words', function() {
	return Redirect::to('/');
});

Route::get('words/{word}', function() {
	return Redirect::to('/');
});

Route::get('menu', function() {
	return Redirect::to('/');
});

Route::group(array('prefix' => 'api'), function() {

	Route::get('/', function() {
		return Response::json([ 'api is running. Use post request to try to auth.' ], 200);
	});

	Route::post('/', array('before' => 'auth', function() {
		return Response::json(['api is running'], 200);
	}));

	Route::resource('words', 'WordCardsController');

	Route::get('randomwords', 'WordCardsController@randomwords');

	Route::get('moderate/words', 'MwordsController@show_all');
	Route::get('moderate/words/{word_id}', 'MwordsController@show');
	Route::delete('moderate/words/{word_id}', 'MwordsController@decline');

	Route::post('moderate/words/{word_id}/changestatus', 'MwordsController@change_status');

	Route::resource('users', 'UsersController');

	Route::post('users/adminauth', 'UsersController@adminauth');

	Route::group(array('prefix' => 'user', 'before' => 'auth'), function() {
		Route::post('{user_id}/subscribe', 'UsersController@subscribe');
		Route::post('{user_id}/unsubscribe', 'UsersController@unsubscribe');
		Route::post('{user_id}/subscriptions', 'UsersController@subscriptions');

		Route::post('{user_id}/addlife', 'UsersController@addlife');
		Route::post('{user_id}/addword', 'MwordsController@add_word');
		Route::post('{user_id}/teststart', 'TestsController@start');
	});

	// Route::get('user/{user_id}/devices', 'UsersController@devices');
	// Route::post('user/{user_id}/adddevice', 'UsersController@add_device');
	// Route::post('user/{user_id}/removedevice', 'UsersController@remove_device');

	Route::get('user/{user_id}/words', 'MwordsController@show_words');
	
	Route::delete('moderate/{word_id}/remove', 'MwordsController@remove_word');

	Route::resource('categories', 'CategoriesController');
	Route::get('/categories/{category_id}/words', 'CategoriesController@show_words');

	Route::post('test/result', 'TestsController@result');

	Route::resource('settings', 'SettingsController');
	Route::get('settings', 'SettingsController@index');
	Route::post('settings', 'SettingsController@update');

});

Route::match(array('GET', 'POST'), '/oauth', 'OauthController@login');

Route::any('{slug}/', function() {
	return View::Make('dashboard');
});