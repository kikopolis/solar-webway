<?php

namespace App\Http\Controllers;

use App\Exceptions\CompanyNotFoundException;
use App\Exceptions\PriceListExpiredException;
use App\Http\Requests\SaveBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
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
    
    public function all(): JsonResponse {
        $bookings = Booking::orderBy('created_at', 'desc')->get();
        return response()->json(BookingResource::collection($bookings));
    }
}
