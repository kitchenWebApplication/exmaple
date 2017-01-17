<?php

/**
 * For guests.
 */
Route::group([
    'prefix'    => 'auth',
    'namespace' => 'Auth'
], function () {

    Route::post('login', 'LoginController@authenticate');
});


/**
 * For auth user.
 */
Route::group([
    'prefix'     => 'auth',
    'namespace'  => 'Auth',
    'middleware' => 'jwt.auth'
], function () {

    Route::get('verify', 'LoginController@verify');
});
