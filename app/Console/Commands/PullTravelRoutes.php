<?php

namespace App\Console\Commands;

use App\Service\TravelRouteLoadingService;
use Illuminate\Console\Command;

class PullTravelRoutes extends Command {
    protected $signature = 'app:pull-travel-routes';
    protected $description = 'Pull travel routes from the travel API.';
    
    public function __construct(private readonly TravelRouteLoadingService $travelRouteLoadingService) {
        parent::__construct();
    }
    
    public function handle(): void {
        $this->info('Pulling travel routes...');
        $this->travelRouteLoadingService->loadTravelRoutes();
        $this->info('Travel routes pulled.');
    }
}
