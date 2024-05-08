<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase {

    protected function registerUser(array $userData) {
        return $this->json('POST', '/api/auth/register', $userData);
    }

    public function createUser($args = []) {
        return User::factory()->create($args);
    }

    public function authUser() {
        $user = $this->createUser();
        $token = JWTAuth::fromUser($user);
        return $token;
    }
}
