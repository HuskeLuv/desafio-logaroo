<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ){}

    public function register(RegistrationRequest $request){
        try {
            $userData = $request->only('name', 'email', 'password');
            $user = $this->authService->register($userData);
            return $user;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function login(LoginRequest $request): JsonResponse {
        try {
            $credentials = $request->only('email', 'password');
            $tokenResponse = $this->authService->login($credentials);
            return response()->json($tokenResponse);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
