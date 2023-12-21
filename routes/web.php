<?php

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
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', fn(): Response => Inertia::render('Dashboard'))->name('dashboard');
});
