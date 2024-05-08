<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
  use RefreshDatabase;

  public function test_registration_with_valid_data() {
    $this->postJson(route('auth.register'),[
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ])->assertCreated();

    $this->assertDatabaseHas('users',['name' => 'Test User']);
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
}

  public function test_registration_with_invalid_data() {
    $invalidUserData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ];

    $response = $this->registerUser($invalidUserData);

    $response->assertStatus(422);
  }
}