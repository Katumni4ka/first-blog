<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'       => fake()->sentence(),
            'content'     => fake()->sentence(),
            'image'       => 'photo1.png',
            'date'        => fake()->dateTimeThisMonth()->format('d/m/y'),
            'views'       => fake()->numberBetween(0, 5000),
            'category_id' => 1,
            'user_id'     => 1,
            'status'      => 1,
            'is_featured' => 0
        ];
    }
}
