<?php

// route guest
Route::middleware('guest')->group(function () {
    Route::post('/login', 'Auth\LoginController@login')->name('.login');
});

// route auth
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
});
