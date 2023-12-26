<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'uuid'         => $this->uuid,
            'company'      => new CompanyResource($this->company),
            'price'        => $this->price,
            'distance'     => $this->leg->routeInfo->distance,
            'flight_start' => $this->flight_start,
            'flight_end'   => $this->flight_end,
            'travel_time'  => $this->flight_end->diffInMinutes($this->flight_start),
        ];
    }
}
