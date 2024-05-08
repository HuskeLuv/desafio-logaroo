<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (){
  return response()->json(['api_name' => 'Desafio-Logaroo', 'api_version' => '1.0.0']);
});

Route::prefix('auth')->group(function (){
  Route::post('register', [AuthController::class, 'register']);
  Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
  Route::prefix('auth')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::delete('logout', [AuthController::class, 'logout']);
  });
});

Route::prefix('posts')->middleware('auth:api')->group(function() {
  Route::get('/', [PostController::class, 'index']);
  Route::post('/',[PostController::class, 'store']);
  Route::put('{post}',[PostController::class, 'update']);
  Route::delete('{post}',[PostController::class, 'destroy']);
});