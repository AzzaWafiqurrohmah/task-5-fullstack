<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Web\CategoryController;
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

    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('datatables', 'datatables')->name('datatables');

        Route::post('store', 'store')->name('store');
        Route::match(['PUT', 'PATCH'], '{category}/update', 'update')->name('update');
        Route::get('{category}', 'show')->name('show');
        Route::delete('{category}', 'destroy')->name('destroy');
    });
});