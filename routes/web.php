<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->controller(UserController::class)->group(function() {
    Route::get('login', 'showloginform')->name('login');
    Route::post('login', 'login');
});

Route::middleware('auth')->group(function(){
    Route::post('logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});