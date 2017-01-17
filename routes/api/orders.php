<?php

Route::group([
    'namespace'  => 'Orders',
    'middleware' => 'jwt.auth'
], function () {

    Route::get('orders', 'OrderController@index');
    Route::post('orders', 'OrderController@store');
    Route::delete('orders/{order_id}', 'OrderController@destroy');
    Route::put('orders/{order_id}/update-status', 'OrderController@updateStatus');
});
