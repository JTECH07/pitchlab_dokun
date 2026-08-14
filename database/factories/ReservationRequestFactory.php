<?php

namespace Database\Factories;

use App\Models\Artisan;
use App\Models\ReservationRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationRequestFactory extends Factory
{
    protected $model = ReservationRequest::class;

    public function definition(): array
    {
        return [
            'artisan_id' => Artisan::factory(),
            'visitor_name' => fake()->name(),
            'visitor_phone' => fake()->numerify('+229 97 ## ## ##'),
            'visitor_email' => fake()->safeEmail(),
            'requested_date' => now()->addWeek()->toDateString(),
            'guests_count' => 2,
            'experience_type' => 'Visite d’atelier',
            'status' => 'pending',
        ];
    }
}
