<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'total_price' => $this->total_price,
            'total_distance' => $this->total_distance,
            'duration_in_minutes' => $this->duration_in_minutes,
            'departure' => $this->departure,
            'arrival' => $this->arrival,
            'legs' => $this->legs,
            'company_name' => $this->company->name,
            'price_list_uuid' => $this->priceList->uuid,
        ];
    }
}
