<?php

namespace App\Service;

use App\Models\Leg;
use App\Models\Planet;
use App\Models\PriceList;
use App\Service\Pathfinder\Node;
use App\Service\Pathfinder\PlanetConnections;
use App\Service\Pathfinder\PlanetNode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TravelRouteService {
    private const PLANET_TOTAL_COUNT                 = 8;
    private const MAX_ROUTES_TO_FIND                 = 3;
    private const MAX_ITERATIONS_FOR_ROUTE_ALGORITHM = 1024;
    
    public function __construct(private readonly PlanetConnections $connections) {}
    
    public function getTravelRoutesBetweenPlanetsByUuid(string $start_uuid, string $end_uuid, int $maxSteps = self::PLANET_TOTAL_COUNT): array {
        $start = Planet::query()->where('uuid', $start_uuid)->firstOrFail();
        $end = Planet::query()->where('uuid', $end_uuid)->firstOrFail();
        return $this->getTravelRoutesBetweenPlanets($start, $end, $maxSteps);
    }
    
    public function getTravelRoutesBetweenPlanets(Planet $start, Planet $end, int $maxSteps = self::PLANET_TOTAL_COUNT): array {
        $planets = Planet::all();
        $nodes = $this->mapPlanetsToNodes($planets);
        $start_node = current(array_filter($nodes, fn(PlanetNode $node) => $node->getId() === $start->id));
        $end_node = current(array_filter($nodes, fn(PlanetNode $node) => $node->getId() === $end->id));
        $potential_routes = $this->calculatePotentialRoutes($start_node, $end_node, $nodes, $maxSteps);
        $current_price_list_routes = $this->filterInvalid($potential_routes);
        if (count($current_price_list_routes) > self::MAX_ROUTES_TO_FIND) {
            $current_price_list_routes = array_slice($current_price_list_routes, 0, self::MAX_ROUTES_TO_FIND);
        }
        return $this->convertRoutes($current_price_list_routes);
    }
    
    /**
     * Filter out routes that are not valid for the current price list
     * and cannot be achieved with a single company
     */
    private function filterInvalid(array $routes): array {
        $price_list = PriceList::query()->latest()->firstOrFail();
        $possible_routes = [];
        foreach ($routes as $key => $route) {
            foreach ($route as $step_key => $planet_id) {
                if ($step_key === array_key_last($route)) {
                    break;
                }
                $to_id = $route[$step_key + 1];
                $leg = Leg::query()
                          ->join('price_lists', 'price_lists.id', '=', 'legs.price_list_id')
                          ->join('route_infos', 'route_infos.leg_id', '=', 'legs.id')
                          ->where('price_list_id', $price_list->id)
                          ->where('route_infos.from_id', $planet_id)
                          ->where('route_infos.to_id', $to_id)
                          ->where('price_lists.valid_until', '>', now())
                          ->first();
                if ($leg === null) {
                    unset($routes[$key]);
                    unset($possible_routes[$key]);
                    break;
                } else {
                    if (!isset($possible_routes[$key])) {
                        $possible_routes[$key] = [];
                    }
                    if (!isset($possible_routes[$key][$step_key])) {
                        $possible_routes[$key][$step_key] = [];
                    }
                    $possible_routes[$key][$step_key]['leg'] = $leg;
                    $possible_routes[$key][$step_key]['providers'] = $leg->providers;
                }
            }
        }
        $valid_routes = [];
        foreach ($possible_routes as $route) {
            $company_ids = [];
            foreach ($route as $key => $step) {
                $provider_array = $step['providers']->toArray();
                if ($key === array_key_first($route)) {
                    $company_ids = array_map(fn($provider) => $provider['company_id'], $provider_array);
                } else {
                    $provider_array = array_map(fn($provider) => $provider['company_id'], $provider_array);
                    $company_ids = array_unique(array_values(array_intersect($company_ids, $provider_array)));
                }
            }
            if (count($company_ids) > 0) {
                $valid_route = [];
                foreach ($route as $step) {
                    $valid_route['legs'][] = $step['leg'];
                }
                $valid_route['companies'] = $company_ids;
                $valid_routes[] = $valid_route;
            }
        }
        return $valid_routes;
    }
    
    /**
     * @param Node|null $start_node
     * @param Node|null $end_node
     * @param array     $nodes
     * @param int       $maxSteps
     *
     * @return array
     */
    private function calculatePotentialRoutes(?Node $start_node, ?Node $end_node, array $nodes, int $maxSteps): array {
        $potential_routes = [];
        $max_iterations = self::MAX_ITERATIONS_FOR_ROUTE_ALGORITHM;
        while ($max_iterations > 0) {
            $steps = $this->getStepsBetweenNodes($start_node, $end_node, $nodes);
            if (count($steps) !== 0
                && count($steps) <= $maxSteps
                && !in_array($steps, $potential_routes, true)) {
                $potential_routes[] = $steps;
                array_multisort($potential_routes, SORT_ASC, $potential_routes);
            }
            $max_iterations--;
        }
        return $potential_routes;
    }
    
    private function convertRoutes(array $routes): array {
        $converted_routes = [];
        foreach ($routes as $route) {
            // for every company, create a route
            foreach ($route['companies'] as $company_id) {
                $provider = $route['legs'][0]->providers->where('company_id', $company_id)->first();
                $route_by_provider = [
                    'distance'     => 0,
                    'company_uuid' => $provider->company->uuid,
                    'company_name' => $provider->company->name,
                    'price'        => 0,
                    'departure'    => $provider->flight_start->toDateTimeString(),
                    'legs'         => [],
                    'travel_time'  => 0,
                ];
                /** @var Leg $leg */
                foreach ($route['legs'] as $leg_key => $leg) {
                    $price = $leg->providers->where('company_id', $provider['company_id'])->first()->price;
                    $distance = $leg->routeInfo->distance;
                    $departure = $leg->providers->where('company_id', $provider['company_id'])->first()->flight_start;
                    $arrival = $leg->providers->where('company_id', $provider['company_id'])->first()->flight_end;
                    $route_by_provider['legs'][] = [
                        'from_id'     => $leg->routeInfo->from_id,
                        'from_name'   => $leg->routeInfo->from->name,
                        'to_id'       => $leg->routeInfo->to_id,
                        'to_name'     => $leg->routeInfo->to->name,
                        'distance'    => $distance,
                        'price'       => $price,
                        'departure'   => $departure->toDateTimeString(),
                        'arrival'     => $arrival->toDateTimeString(),
                        'travel_time' => $arrival->diffInMinutes($departure),
                    ];
                    $route_by_provider['distance'] += $distance;
                    $route_by_provider['price'] += $price;
                    $route_by_provider['travel_time'] += $arrival->diffInMinutes($departure);
                    if (array_key_last($route['legs']) === $leg_key) {
                        $route_by_provider['arrival'] = $leg->providers->where('company_id', $provider['company_id'])->first()->flight_end->toDateTimeString();
                    }
                }
                if (!$this->routeHasInvalidLayovers($route_by_provider)) {
                    $converted_routes[] = $route_by_provider;
                }
            }
        }
        return array_values(array_unique($converted_routes, SORT_REGULAR));
    }
    
    private function routeHasInvalidLayovers(array $route): bool {
        foreach ($route['legs'] as $key => $leg) {
            if (array_key_last($route['legs']) !== $key) {
                $arrival = Carbon::parse($leg['arrival']);
                $departure = Carbon::parse($route['legs'][$key + 1]['departure']);
                if ($arrival->gt($departure)) {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * @param Collection<Planet> $planets
     *
     * @return array<Node>
     */
    private function mapPlanetsToNodes(Collection $planets): array {
        return $planets->map(function (Planet $planet): PlanetNode {
            $node = new PlanetNode($planet->id, $planet->name);
            $connections = $this->connections->getConnectionsForPlanet($planet->name);
            foreach ($connections as $connection) {
                $planet = Planet::query()->where('name', $connection)->firstOrFail();
                $node->addConnection(new PlanetNode($planet->id, $planet->name));
            }
            return $node;
        })->toArray();
    }
    
    private function getStepsBetweenNodes(Node $start, Node $end, array $nodes, int $maxSteps = self::PLANET_TOTAL_COUNT): array {
        $steps = [];
        $current_node = $start;
        $iterations = 0;
        while ($current_node->getId() !== $end->getId() && $iterations < $maxSteps) {
            $steps[] = $current_node->getId();
            $max_possible_connections = count($current_node->getConnections());
            if ($max_possible_connections === 0) {
                return [];
            }
            $possible_connections = $current_node->getConnections();
            foreach ($possible_connections as $possible_connection) {
                $possible_steps = $this->getStepsBetweenNodes($possible_connection, $end, $nodes, $maxSteps - count($steps));
                if (count($possible_steps) !== 0) {
                    return array_merge($steps, $possible_steps);
                }
            }
            $possible_conn_id = (int) $possible_connections[array_rand($possible_connections)]->getId();
            $node = array_filter($nodes, fn(Node $node) => $node->getId() === $possible_conn_id);
            $node = array_pop($node);
            $current_node = $node;
            $iterations++;
        }
        $steps[] = $end->getId();
        return $steps;
    }
}
