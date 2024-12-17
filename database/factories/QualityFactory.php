<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class QualityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arr = ['480p', '144p', '240p', '360p', '720p', '1080p', '2040p'];
        return [
            'quality' => array_rand($arr),
            'link_download' => fake()->imageUrl,
            'videos_id' => fake()->numberBetween(1, 10)
        ];
    }
}
