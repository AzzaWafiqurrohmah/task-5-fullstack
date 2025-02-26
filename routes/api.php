<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('/v1')-> group(function() {

    Route::controller(UserController::class)->prefix('/user')->group(function (){
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });

    Route::middleware('auth:api')->group(function () {
        
        Route::controller(CategoryController::class)->prefix('/category')->group(function (){

            Route::delete('/delete/{category}', 'delete')->missing(function (){
                return response()->json(['data' => 'This Category was not found']);
            });

            Route::put('/update/{category}', 'update')->missing(function (){
                return response()->json(['data' => 'This Category was not found']);
            });

            Route::post('/detail/{category}', 'show')->missing(function (){
                return response()->json(['data' => 'This Category was not found']);
            });

            Route::post('/create', 'create');
            Route::get('/listAll', 'listAll');
        });
    });

});