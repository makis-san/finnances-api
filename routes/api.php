<?php

use Illuminate\Http\Request;


Route::group(['middleware' => ['optJwt']], function () {
    Route::post('/auth', 'AuthController@login');
    Route::post('/register', 'UserController@store');
    Route::post('/resetPassword', 'AuthController@SendResetPasswordToken');
    Route::post('/resetPassword/{token}', 'AuthController@ResetPasswordByToken');
});

Route::group(['middleware' => ['apijwt']], function () {
    Route::get('/verify', 'AuthController@verify');
    Route::get('/users/{id}', 'UserController@show');
    Route::get('/users', 'UserController@index');
});
