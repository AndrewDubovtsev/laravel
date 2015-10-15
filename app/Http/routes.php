<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    if (Auth::check()) {
            return view('welcome');
        } else {
            return view('unauth');
    }
});

Route::get('/registration', function () {
    if (Auth::check()) {
        return view('logged-in');
    } else {
        return view('registration');
    }
});



