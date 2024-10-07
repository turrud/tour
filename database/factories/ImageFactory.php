<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(),
            'post_id' => \App\Models\Post::factory(),
        ];
    }
}
