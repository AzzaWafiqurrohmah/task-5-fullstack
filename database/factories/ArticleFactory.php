<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $user_id, $category_id;

    public function withCustom($user_id, $category_id)
    {
        return $this->state([
            'user_id' => $user_id,
            'category_id' => $category_id
        ]);
    }


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' => $user_id,
            // 'category_id' => $this->category_id,
            'title' => $this->faker->word,
            'content' => $this->faker->word(),
            'image' => UploadedFile::fake()->image('article1.jpg')
        ];
    }
}
