<?php

namespace App\Console\Commands;

use App\Models\PriceList;
use App\Service\TravelRouteLoadingService;
use Illuminate\Console\Command;

class PullTravelRoutes extends Command {
    protected $signature = 'travel:pull';
    protected $description = 'Pull travel routes from the travel API.';
    
    public function __construct(private readonly TravelRouteLoadingService $travelRouteLoadingService) {
        parent::__construct();
    }
    
    public function handle(): void {
        $latest_price_list = PriceList::latest('valid_until')->firstOrFail();
        if ($latest_price_list->isExpired()) {
            $this->info('Pulling travel routes...');
            $this->travelRouteLoadingService->loadTravelRoutes();
            $this->info('Travel routes pulled.');
        }
    }
}
