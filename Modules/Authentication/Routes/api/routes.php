<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::post( 'send-otp', 'LoginController@sendingOtp')->name('api.auth.send.otp');
    Route::post('login', 'LoginController@postLogin')->name('api.auth.login');
    Route::post('register', 'RegisterController@register')->name('api.auth.register');
    Route::post('forget-password', 'ForgotPasswordController@forgetPassword')->name('api.auth.password.forget');

    Route::group(['prefix' => '/', 'middleware' => 'auth:sanctum'], function () {

        Route::post('logout', 'LoginController@logout')->name('api.auth.logout');
    });
});
