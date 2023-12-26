<?php

namespace App\Service\Pathfinder;

interface Node {
    public function getId(): int;
    
    /**
     * @return Node[]
     */
    public function getConnections(): array;
    
    public function addConnection(Node $connection): void;
    
    /**
     * @param Node[] $connections
     */
    public function setConnections(array $connections): void;
}
