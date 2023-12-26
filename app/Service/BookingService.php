<?php

namespace App\Service;

use App\Exceptions\CompanyNotFoundException;
use App\Exceptions\PriceListExpiredException;
use App\Http\Requests\SaveBookingRequest;
use App\Models\Booking;
use App\Models\Company;
use App\Models\PriceList;
use Str;

class BookingService {
    /**
     * @throws PriceListExpiredException|CompanyNotFoundException
     */
    public function book(SaveBookingRequest $request): Booking {
        $company = $this->getCompany($request);
        $price_list = $this->getPriceList($request);
        return Booking::create(
            [
                'first_name'          => $request->input('first_name'),
                'last_name'           => $request->input('last_name'),
                'company_id'          => $company->id,
                'price_list_id'       => $price_list->id,
                'departure'           => $request->input('departure'),
                'arrival'             => $request->input('arrival'),
                'legs'                => $request->input('legs'),
                'total_price'         => $request->input('price'),
                'total_distance'      => $request->input('distance'),
                'duration_in_minutes' => $request->input('travel_time'),
                'uuid'                => Str::uuid(),
            ]
        );
    }
    
    /**
     * @throws PriceListExpiredException
     */
    private function getPriceList(SaveBookingRequest $request): PriceList {
        $uuid = $request->input('price_list_uuid');
        $price_list = PriceList::where('uuid', $uuid)->first();
        if (!$price_list || $price_list->isExpired()) {
            throw new PriceListExpiredException($uuid);
        }
        return $price_list;
    }
    
    /**
     * @throws CompanyNotFoundException
     */
    private function getCompany(SaveBookingRequest $request): Company {
        $uuid = $request->input('company_uuid');
        $company = Company::where('uuid', $uuid)->first();
        if (!$company) {
            throw new CompanyNotFoundException($uuid);
        }
        return $company;
    }
}
