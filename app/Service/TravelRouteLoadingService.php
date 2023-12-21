<?php

namespace App\Service;

use App\Models\Company;
use App\Models\Leg;
use App\Models\Planet;
use App\Models\PriceList;
use App\Models\RouteInfo;
use App\Models\Provider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;
use RuntimeException;

class TravelRouteLoadingService {
    private const API_URL = 'https://cosmos-odyssey.azurewebsites.net/api/v1.0/TravelPrices';
    
    public function loadTravelRoutes(): void {
        $response = Http::get(self::API_URL)->body();
        try {
            $result = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            $priceList = $this->savePriceList($result['id'], $result['validUntil']);
            $this->saveLegs($result['legs'], $priceList);
        } catch (JsonException $e) {
            Log::error($e->getMessage());
            throw new RunTimeException('Error while parsing travel routes');
        }
    }
    
    private function savePriceList(string $uuid, string $validUntil): PriceList {
        $priceList = PriceList::where('uuid', $uuid)->first();
        if ($priceList === null) {
            $priceList = PriceList::create(['uuid' => $uuid, 'valid_until' => $validUntil]);
        }
        return $priceList;
    }
    
    private function saveLegs(mixed $travelLegs, PriceList $priceList): void {
        foreach ($travelLegs as $travelLeg) {
            $leg = $this->saveLeg($travelLeg['id'], $priceList);
            $this->saveRouteInfo($travelLeg['routeInfo'], $leg);
            $this->saveProviders($travelLeg['providers'], $leg);
        }
    }
    
    private function saveLeg(string $uuid, PriceList $priceList): Leg {
        $leg = Leg::where('uuid', $uuid)->first();
        if ($leg === null) {
            $leg = Leg::create(['uuid' => $uuid, 'price_list_id' => $priceList->id]);
        }
        return $leg;
    }
    
    private function saveRouteInfo(array $data, Leg $leg): void {
        $info = RouteInfo::where('uuid', $data['id'])->first();
        if ($info === null) {
            $from = $this->savePlanet($data['from']);
            $to = $this->savePlanet($data['to']);
            $info = RouteInfo::create(
                [
                    'uuid'     => $data['id'],
                    'from_id'  => $from->id,
                    'to_id'    => $to->id,
                    'distance' => $data['distance'],
                    'leg_id'   => $leg->id,
                ]
            );
        }
    }
    
    private function savePlanet(array $data): Planet {
        $planet = Planet::where('uuid', $data['id'])
                        ->orWhere('name', $data['name'])
                        ->first();
        if ($planet === null) {
            $planet = Planet::create(['uuid' => $data['id'], 'name' => $data['name']]);
        }
        return $planet;
    }
    
    private function saveProviders(mixed $providers, Leg $leg): void {
        foreach ($providers as $provider) {
            $this->saveProvider($provider, $leg);
        }
    }
    
    private function saveProvider(array $data, Leg $leg): void {
        $provider = Provider::where('uuid', $data['id'])->first();
        $company = $this->saveCompany($data['company']);
        if ($provider === null) {
            Provider::create(
                [
                    'uuid'         => $data['id'],
                    'leg_id'       => $leg->id,
                    'price'        => $data['price'],
                    'flight_start' => $data['flightStart'],
                    'flight_end'   => $data['flightEnd'],
                    'company_id'   => $company->id,
                ]
            );
        }
    }
    
    private function saveCompany(array $data) {
        $company = Company::where('uuid', $data['id'])->first();
        if ($company === null) {
            $company = Company::create(['uuid' => $data['id'], 'name' => $data['name']]);
        }
        return $company;
    }
}
