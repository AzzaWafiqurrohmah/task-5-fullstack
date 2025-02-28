<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    protected $token, $user, $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            'email' => 'category@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        $response = $this->postJson('/api/v1/user/login', [
            'email' => 'category@gmail.com',
            'password' => '12345678'
        ]); 

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);

        $this->token = $response->json()['data']['token'];
    }

    public function test_create_category()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/v1/category/create', [
            'user_id' => $this->user->id,
            'name' => 'externall123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_show_category_valid_id()
    {
        $this->category = Category::factory()->withCustomUser($this->user->id)->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson("/api/v1/category/detail/{$this->category->id}", []);

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_show_category_invalid_id()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson("/api/v1/category/detail/100", []);

        $response->assertJsonStructure(['errors']);
    }

    public function test_listAll_pagination()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/v1/category/listAll", []);

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_update_category_success()
    {
        $this->category = Category::factory()->withCustomUser($this->user->id)->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson("/api/v1/category/update/{$this->category->id}", [
            'name' => $this->category->name . '123'  //Try to using a different name with others
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_update_category_invalid_id()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson("/api/v1/category/update/555", [
            'name' => 'example'
        ]);

        $response->assertJsonStructure(['errors']);
    }

    public function test_delete_category_valid_id()
    {
        $this->category = Category::factory()->withCustomUser($this->user->id)->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/v1/category/delete/{$this->category->id}", []);

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_delete_category_invalid_id()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/v1/category/delete/9999", []);

        $response->assertJsonStructure(['errors']);
    }

    public function tearDown(): void
    {
        if ($this->user) {
            $this->user->delete();
        }

        if($this->category){
            $this->category->delete();
        }

        parent::tearDown();
    }


}
