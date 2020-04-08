<?php

// route guest
Route::middleware('guest')->group(function () {
    Route::get('/login', 'Auth\LoginController@index')->name('login');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

// route auth
Route::middleware('auth')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::name('account')->prefix('account')->group(function () {
        Route::get('/', 'AccountController@index');
        Route::put('/', 'AccountController@update')->middleware('ajax');
    });

    Route::name('users')->prefix('users')->group(function () {
        Route::get('/', 'UserController@index');
        Route::post('/', 'UserController@store')->middleware('ajax');
        Route::put('/', 'UserController@update')->middleware('ajax');
        Route::delete('/', 'UserController@destroy')->middleware('ajax');

        Route::get('/data', 'UserController@data')->name('.data')->middleware('ajax');
        Route::post('/show', 'UserController@show')->name('.show')->middleware('ajax');

        Route::post('/manage', 'UserController@getManage')->name('.manage')->middleware('ajax');
        Route::put('/manage', 'UserController@manage')->name('.manage')->middleware('ajax');

        Route::post('/select2/roles', 'UserController@select2Roles')->name('.select2.roles')->middleware('ajax');
    });

    Route::name('roles')->prefix('roles')->group(function () {
        Route::get('/', 'RoleController@index');
        Route::post('/', 'RoleController@store')->middleware('ajax');
        Route::put('/', 'RoleController@update')->middleware('ajax');
        Route::delete('/', 'RoleController@destroy')->middleware('ajax');

        Route::get('/data', 'RoleController@data')->name('.data')->middleware('ajax');
        Route::post('/show', 'RoleController@show')->name('.show')->middleware('ajax');

        Route::post('/default-user', 'RoleController@setDefault')->name('.default')->middleware('ajax');

        Route::post('/manage', 'RoleController@getManage')->name('.manage')->middleware('ajax');
        Route::put('/manage', 'RoleController@manage')->name('.manage')->middleware('ajax');
    });

    Route::name('permissions')->prefix('permissions')->group(function () {
        Route::get('/', 'PermissionController@index');
        Route::post('/', 'PermissionController@store')->middleware('ajax');
        Route::put('/', 'PermissionController@update')->middleware('ajax');
        Route::delete('/', 'PermissionController@destroy')->middleware('ajax');

        Route::get('/data', 'PermissionController@data')->name('.data')->middleware('ajax');
        Route::post('/show', 'PermissionController@show')->name('.show')->middleware('ajax');
    });

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
});
