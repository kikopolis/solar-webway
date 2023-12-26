<?php

namespace Database\Factories;

use App\Models\PriceList;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PriceListFactory extends Factory {
    protected $model = PriceList::class;
    
    public function definition(): array {
        return [
            'uuid'        => $this->faker->uuid(),
            'valid_until' => Carbon::now()->addDay(),
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ];
    }
}
