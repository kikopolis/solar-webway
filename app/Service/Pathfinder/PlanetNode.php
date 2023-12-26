<?php

namespace App\Service\Pathfinder;

class PlanetNode implements Node {
    private array $connections;
    
    public function __construct(private readonly int $id, private readonly string $name) {
        $this->connections = [];
    }
    
    public function getId(): int {
        return $this->id;
    }
    
    public function getName(): string {
        return $this->name;
    }
    
    /**
     * @return Node[]
     */
    public function getConnections(): array {
        return $this->connections;
    }
    
    public function addConnection(Node $connection): void {
        $this->connections[] = $connection;
    }
    
    /**
     * @param Node[] $connections
     */
    public function setConnections(array $connections): void {
        $this->connections = $connections;
    }
}
