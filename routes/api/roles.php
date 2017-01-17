<?php

Route::group([
    'namespace'  => 'Users',
    'middleware' => 'jwt.auth'
], function () {

    Route::get('roles', 'RoleController@index');
});
