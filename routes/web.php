<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('landing.home-index');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.registrasi');
})->name('register');