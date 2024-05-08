<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (){
  return response()->json(['api_name' => 'Desafio-Logaroo', 'api_version' => '1.0.0']);
});

Route::prefix('auth')->group(function (){
  Route::post('register', [AuthController::class, 'register'])->name('auth.register');
  Route::post('login', [AuthController::class, 'login'])->name('auth.login');
});

Route::middleware('auth:api')->group(function () {
  Route::prefix('auth')->group(function () {
    Route::get('me', [AuthController::class, 'me'])->name('auth.me');
    Route::delete('logout', [AuthController::class, 'logout'])->name('auth.logout');
  });
});

Route::prefix('posts')->middleware('auth:api')->group(function() {
  Route::get('/', [PostController::class, 'index'])->name('posts.index');
  Route::post('/',[PostController::class, 'store'])->name('posts.store');
  Route::put('{post}',[PostController::class, 'update'])->name('posts.update');
  Route::delete('{post}',[PostController::class, 'destroy'])->name('posts.destroy');
});