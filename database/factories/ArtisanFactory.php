<?php

namespace Database\Factories;

use App\Models\Artisan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtisanFactory extends Factory
{
    protected $model = Artisan::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'professional_name' => fake()->company(),
            'phone' => fake()->unique()->numerify('+229 97 ## ## ##'),
            'whatsapp' => fake()->numerify('+22997######'),
            'description' => fake()->paragraph(),
            'history' => fake()->paragraph(),
            'experience_years' => fake()->numberBetween(1, 30),
            'address' => 'Porto-Novo, Bénin',
            'latitude' => 6.49,
            'longitude' => 2.62,
            'status' => 'draft',
        ];
    }
}
