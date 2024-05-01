<?php

namespace App\Services\Auth;

use App\Models\User;
use Exception;

class AuthService {
  public function register(array $userData): User {
    if(!$user = User::create($userData)){
      throw new Exception('Unable to create User');
    }
    return $user;
  }

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