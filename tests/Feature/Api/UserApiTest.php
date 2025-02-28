<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    public function test_register_success(): void
    {
        $response = $this->postJson('/api/v1/user/register', [
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ]); 

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_register_invalid_email(): void
    {
        $response = $this->postJson('/api/v1/user/register', [
            'email' => 'johndoe',
            'password' => 'password'
        ]);

        $response->assertStatus(400)
                 ->assertJsonStructure(['errors']);
    }

    public function test_login_success(): void
    {
        $response = $this->postJson('/api/v1/user/login', [
            'email' => 'johndoe@example.com',
            'password' => 'password'
        ]); 

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_login_wrong_password(): void
    {
        $response = $this->postJson('/api/v1/user/login', [
            'email' => 'johndoe@example.com',
            'password' => 'passwo23'
        ]); 

        $response->assertStatus(401)
                 ->assertJsonStructure(['errors']);

        User::where('email', 'johndoe@example.com')->delete();
    }
}
