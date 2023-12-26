<?php

namespace Database\Factories;

use App\Models\Leg;
use App\Models\Planet;
use App\Models\RouteInfo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RouteInfoFactory extends Factory {
    protected $model = RouteInfo::class;
    
    public function definition(): array {
        $from_id = Planet::inRandomOrder()->first();
        $to_id = Planet::inRandomOrder()->first();
        while ($from_id->id === $to_id->id) {
            $to_id = Planet::inRandomOrder()->first();
        }
        return [
            'uuid'       => $this->faker->uuid(),
            'distance'   => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'from_id'    => $from_id,
            'to_id'      => $to_id,
            'leg_id'     => Leg::factory(),
        ];
    }
}
