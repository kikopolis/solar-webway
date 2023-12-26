<?php

namespace App\Service\Pathfinder;

use App\Models\Planet;
use Str;

class PlanetConnections {
    private array $connections;
    
    public function __construct(array $connections = []) {
        if (count($connections) > 0) {
            $this->connections = $connections;
        } else {
            $this->connections = [
                Planet::MERCURY => [Planet::VENUS],
                Planet::VENUS   => [Planet::MERCURY, Planet::EARTH],
                Planet::EARTH   => [Planet::JUPITER, Planet::URANUS],
                Planet::MARS    => [Planet::VENUS],
                Planet::JUPITER => [Planet::MARS, Planet::VENUS],
                Planet::SATURN  => [Planet::EARTH, Planet::NEPTUNE],
                Planet::URANUS  => [Planet::SATURN, Planet::NEPTUNE],
                Planet::NEPTUNE => [Planet::URANUS, Planet::MERCURY],
            ];
        }
    }
    
    public function getConnectionsForPlanet(string $planetName): array {
        return $this->getConnections()[Str::lower($planetName)] ?? [];
    }
    
    public function getConnections(): array {
        return $this->connections;
    }
}
