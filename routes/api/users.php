<?php

Route::group([
    'namespace'  => 'Users',
    'middleware' => ['jwt.auth', 'role:manager']
], function () {

    Route::get('users', 'UserController@index');
    Route::get('users/{user_id}', 'UserController@show');
    Route::post('users', 'UserController@store');
    Route::put('users/{user_id}', 'UserController@update');
    Route::delete('users/{user_id}', 'UserController@destroy');
});
