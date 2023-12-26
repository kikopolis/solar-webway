<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteInfoResource extends JsonResource {
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'uuid'     => $this->uuid,
            'from'     => new PlanetResource($this->from),
            'to'       => new PlanetResource($this->to),
            'distance' => $this->distance,
        ];
    }
}
