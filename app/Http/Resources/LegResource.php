<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LegResource extends JsonResource {
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'uuid'      => $this->uuid,
            'routeInfo' => new RouteInfoResource($this->routeInfo),
            'providers' => ProviderResource::collection($this->providers),
        ];
    }
}
