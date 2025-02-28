<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Repository\ArticleRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    protected $token, $user, $image, $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            'email' => 'johhhhnii2i@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        $this->category = Category::factory()->withCustomUser($this->user->id)->create();

        $response = $this->postJson('/api/v1/user/login', [
            'email' => 'johhhhnii2i@gmail.com',
            'password' => '12345678'
        ]); 

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);

        $this->token = $response->json()['data']['token'];
    }

    public function test_create_article()
    {
        $file = UploadedFile::fake()->image('article1.jpg');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/v1/article/create', [
            'category_id' => $this->category->id,
            'title' => 'Apa yg dimaksud Animal ?',
            'content' => 'Animal adalah hewan',
            'image' => $file
        ]);

        $this->image = $response->json()['data']['image'];
        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_show_article_valid_id()
    {
        $article = Article::factory()->withCustom($this->user->id, $this->category->id)->create();
        $this->image = $article->image;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson("/api/v1/article/detail/{$article->id}", []);

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_show_article_invalid_id()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson("/api/v1/article/detail/999", []);

        $response->assertJsonStructure(['errors']);
    }

    public function test_listAll_pagination()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/v1/article/listAll", []);

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_update_article_success()
    {
        $article = Article::factory()->withCustom($this->user->id, $this->category->id)->create();
        $this->image = $article->image;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson("/api/v1/article/update/{$article->id}", [
            'category_id' => $this->category->id,
            'title' => 'Apa yg dimaksud Animal 12 ?',
            'content' => 'Animal adalah hewan, iya kah?',
            'image' => $this->image
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_update_article_invalid_id()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson("/api/v1/article/update/99", [
            'category_id' => $this->category->id,
            'title' => 'Apa yg dimaksud Animal 12 ?',
            'content' => 'Animal adalah hewan, iya kah?'
        ]);

        $response->assertJsonStructure(['errors']);
    }

    public function test_delete_article_valid_id()
    {
        $article = Article::factory()->withCustom($this->user->id, $this->category->id)->create();
        $this->image = $article->image;
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/v1/article/delete/{$article->id}", []);

        $response->assertStatus(200)
                 ->assertJsonStructure(['meta', 'data']);
    }

    public function test_delete_article_invalid_id()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/v1/article/delete/999", []);

        $response->assertJsonStructure(['errors']);
    }

    public function tearDown(): void
    {
        if($this->image){
            Storage::disk('public')->delete($this->image);
        }
        
        if ($this->user) {
            $this->user->delete();
        }

        parent::tearDown();
    }
}
