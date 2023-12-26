<?php

namespace App\Http\Controllers;

use App\Exceptions\CompanyNotFoundException;
use App\Exceptions\PriceListExpiredException;
use App\Http\Requests\SaveBookingRequest;
use App\Service\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller {
    public function __construct(private readonly BookingService $bookingService,) {}
    
    public function bookNewTravelRoute(SaveBookingRequest $request): JsonResponse {
        try {
            $booking = $this->bookingService->book($request);
        } catch (PriceListExpiredException $e) {
            abort(400, $e->getMessage());
        } catch (CompanyNotFoundException $e) {
            abort(500, $e->getMessage());
        }
        return response()->json(['booking_uuid' => $booking->uuid]);
    }
}
