<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('/v1')-> group(function() {
    Route::controller(UserController::class)->group(function (){
        Route::post('/register', 'register');
    });
});