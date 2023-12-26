<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceListResource extends JsonResource {
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'uuid'        => $this->uuid,
            'valid_until' => $this->valid_until,
            'legs'        => LegResource::collection($this->legs),
        ];
    }
}
