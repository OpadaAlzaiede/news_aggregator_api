<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(10),
            'description' => fake()->text(100),
            'content' => fake()->text(200),
            'category' => fake()->text(10),
            'author' => fake()->name(),
            'source' => fake()->text(10),
            'published_at' => fake()->date(),
        ];
    }
}
