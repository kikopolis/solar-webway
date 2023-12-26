<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use App\Service\TravelRouteLoadingService;
use App\Service\TravelRouteService;
use Illuminate\Http\JsonResponse;

class TravelRouteController extends Controller {
    public function __construct(
        private readonly TravelRouteLoadingService $travelRouteLoadingService,
        private readonly TravelRouteService        $planetPathfinderService
    ) {}
    
    public function getTravelRoutesBetweenPlanets(string $start, string $end): JsonResponse {
        $price_list = PriceList::latest('valid_until')->firstOrFail();
        if ($price_list->isExpired()) {
            $this->travelRouteLoadingService->loadTravelRoutes();
            $price_list = PriceList::latest('valid_until')->firstOrFail();
        }
        $routes = $this->planetPathfinderService->getTravelRoutesBetweenPlanetsByUuid($start, $end);
        return response()->json(
            [
                'price_list_uuid' => $price_list->uuid,
                'routes'          => $routes,
            ]
        );
    }
}
