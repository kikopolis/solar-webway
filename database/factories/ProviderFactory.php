<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Leg;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProviderFactory extends Factory {
    protected $model = Provider::class;
    
    public function definition(): array {
        return [
            'uuid'         => $this->faker->uuid(),
            'price'        => $this->faker->randomFloat(),
            'flight_start' => Carbon::now(),
            'flight_end'   => Carbon::now(),
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
            'company_id'   => Company::inRandomOrder()->first(),
            'leg_id'       => Leg::inRandomOrder()->first(),
        ];
    }
}
