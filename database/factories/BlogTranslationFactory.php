<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogTranslation>
 */
class BlogTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                'locale' => 'en',
        'name' => $this->faker->sentence(),
        'body' => $this->faker->paragraphs(5, true),
            ];
    }
}
