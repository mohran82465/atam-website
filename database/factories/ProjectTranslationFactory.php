<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectTranslation>
 */
class ProjectTranslationFactory extends Factory
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
            'body' => $this->faker->paragraph(),
            'problem' => $this->faker->sentence(),
            'solve' => $this->faker->sentence(),
            'tech' => ['Laravel', 'Vue', 'Tailwind']    
        ];
    }
}
