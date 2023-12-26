<?php

namespace Database\Factories;

use App\Models\Leg;
use App\Models\PriceList;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LegFactory extends Factory {
    protected $model = Leg::class;
    
    public function definition(): array {
        return [
            'uuid'       => $this->faker->uuid(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'price_list_id' => PriceList::inRandomOrder()->first()->id,
        ];
    }
}
