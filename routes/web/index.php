<?php

require __DIR__ . '/../patterns.php';

Route::get('{entity}/{params?}', function () {
    return view('app');
})->where([
    'entity' => '/|dashboard|',
    'params' => '[\w\.\/-=]+'
]);
