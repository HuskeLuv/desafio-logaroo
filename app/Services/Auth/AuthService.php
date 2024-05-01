<?php

namespace App\Services\Auth;

use Exception;

class AuthService {

  public function login(array $credentials){
    if(!$token = auth()->attempt($credentials)){ 
      throw new Exception('Unauthorized');
    }    
    return $this->respondWithToken($token);
  }

  protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}