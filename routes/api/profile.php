<?php

Route::group([
    'namespace'  => 'Users',
    'middleware' => 'jwt.auth'
], function () {

    Route::get('profile', 'ProfileController@index');
    Route::put('profile', 'ProfileController@update');
});
