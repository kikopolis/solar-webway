<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\TravelRouteController;
use App\Http\Resources\PlanetResource;
use App\Models\Planet;
use App\Models\PriceList;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', fn() => Inertia::render('Welcome'))->name('welcome');
Route::get('/booking', fn() => Inertia::render('Booking'))->name('booking');
Route::get('/planets', fn() => PlanetResource::collection(Planet::all()))->name('planets');
Route::get('/price-lists', fn() => PriceList::getLatestAsResource())->name('price-lists');
Route::get('/routes/{from_uuid}/{to_uuid}', [TravelRouteController::class, 'getTravelRoutesBetweenPlanets'])->name('routes');
Route::post('/bookings', [BookingController::class, 'bookNewTravelRoute'])->name('bookings.add');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', fn(): Response => Inertia::render('Dashboard'))->name('dashboard');
});
