<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddJob>
 */
class AddJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle,
            'user_id' => rand(1,3),
            'job_type_id' => rand(1,4),
            'category_id' => rand(1,5),
            'vacancy' => 3,
            'location' => fake()->city,
            'description' => fake()->paragraph,
            'benefits' => fake()->sentence,
            'responsibility' => fake()->sentence,
            'qualifications' => fake()->sentence,
            'keywords' => fake()->text,
            'experience' => rand(1,10),
            'company_name' => fake()->company,
            'company_location' => fake()->city,
            'company_website' => fake()->url,
        ];
    }
}
