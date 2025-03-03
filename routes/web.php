<?php

use App\Http\Controllers\Web\ArticleController;
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
    Route::get('logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::controller(CategoryController::class)
    ->prefix('categories')
    ->name('categories.')
     ->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('datatables', 'datatables')->name('datatables');

        Route::post('store', 'store')->name('store');
        Route::match(['PUT', 'PATCH'], '{category}/update', 'update')->name('update');
        Route::get('{category}', 'show')->name('show');
        Route::delete('{category}', 'destroy')->name('destroy');
    });

    Route::controller(ArticleController::class)
    ->prefix('articles')
    ->name('articles.')
    ->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('datatables', 'datatables')->name('datatables');

        Route::get('/get', 'get')->name('get');
        Route::get('search', 'search')->name('search');

        Route::get('/{article}/edit', 'edit')->name('edit');
        Route::post('store', 'store')->name('store');
        Route::match(['PUT', 'PATCH'], '{article}/update', 'update')->name('update');
        Route::get('{article}', 'show')->name('show');
        Route::delete('{article}', 'destroy')->name('destroy');
    });

});