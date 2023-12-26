<?php

namespace App\Console\Commands;

use App\Service\TravelRouteLoadingService;
use Illuminate\Console\Command;

class PurgeOldTravelRoutes extends Command {
    protected $signature = 'travel:purge';
    protected $description = 'Purge old expired travel routes.';
    
    public function __construct(private readonly TravelRouteLoadingService $travelRouteLoadingService) {
        parent::__construct();
    }
    
    public function handle(): void {
        $this->info('Purging old travel routes...');
        $this->travelRouteLoadingService->purgeOldTravelRoutes();
        $this->info('Travel routes purged.');
    }
}
