<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (){
  return response()->json(['api_name' => 'Desafio-Logaroo', 'api_version' => '1.0.0']);
});

Route::prefix('auth')->group(function (){
  Route::post('register', [AuthController::class, 'register']);
  Route::post('login', [AuthController::class, 'login']);
  Route::get('me', [AuthController::class, 'me']);
});