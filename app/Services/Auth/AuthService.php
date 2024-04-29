<?php

namespace App\Services\Auth;

class AuthService {
  public function login(array $credentials){
    if(!$token = auth()->attempt($credentials)){ 
    return response()->json(['error' => 'Unauthorized'], 401);
    }    
    return response()->json([
        'acess_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60
      ]);
  }
}