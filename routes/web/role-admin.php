<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'role:sysadmin,admin',
    'namespace' => 'Admin',
    'prefix' => 'admin',
], function () {
    Route::get('images', 'DarwinCoreSimpleImageController@index');
    Route::post('images', 'DarwinCoreSimpleImageController@store');
    Route::put('images/{id}', 'DarwinCoreSimpleImageController@update');
    Route::delete('images/{id}', 'DarwinCoreSimpleImageController@destroy');
});
