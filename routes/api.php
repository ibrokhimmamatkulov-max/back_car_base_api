<?php

use App\Http\Controllers\Api\ApplicationStatusController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\GearboxController;
use App\Http\Controllers\Api\PerformerTransportPhotoController;
use App\Http\Controllers\Api\RentalApplicationController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\RentalStatusController;
use App\Http\Controllers\Api\RentalTariffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('cars', [CarController::class, 'index'])->name('cars.index');
Route::post('cars', [CarController::class, 'store'])->name('cars.store');
Route::patch('cars/{id}', [CarController::class, 'update'])->name('cars.update');
Route::get('cars/{id}/show', [CarController::class, 'edit'])->name('cars.show');
Route::get('cars/fuel-types', [CarController::class, 'fuel_types']);
Route::get('cars/dop-options', [CarController::class, 'car_dop_options']);

Route::get('rental-tariffs', [RentalTariffController::class, 'index']);
Route::post('rental-tariffs', [RentalTariffController::class, 'store']);
Route::get('rental-tariffs/{id}', [RentalTariffController::class, 'show']);
Route::put('rental-tariffs/{id}', [RentalTariffController::class, 'update']);
Route::delete('rental-tariffs/{id}', [RentalTariffController::class, 'destroy']);

Route::get('cities', [CityController::class, 'index']);
Route::post('cities', [CityController::class, 'store']);
Route::get('cities/{id}', [CityController::class, 'show']);
Route::put('cities/{id}', [CityController::class, 'update']);
Route::delete('cities/{id}', [CityController::class, 'destroy']);

Route::get('application-statuses', [ApplicationStatusController::class, 'index']);
Route::post('application-statuses', [ApplicationStatusController::class, 'store']);
Route::get('application-statuses/{id}', [ApplicationStatusController::class, 'show']);
Route::put('application-statuses/{id}', [ApplicationStatusController::class, 'update']);
Route::delete('application-statuses/{id}', [ApplicationStatusController::class, 'destroy']);

Route::get('gearboxes', [GearboxController::class, 'index']);
Route::post('gearboxes', [GearboxController::class, 'store']);
Route::get('gearboxes/{id}', [GearboxController::class, 'show']);
Route::put('gearboxes/{id}', [GearboxController::class, 'update']);
Route::delete('gearboxes/{id}', [GearboxController::class, 'destroy']);

Route::get('car-photos/{car_id}', [PerformerTransportPhotoController::class, 'index']);
Route::post('car-photos', [PerformerTransportPhotoController::class, 'store']);
Route::delete('car-photos/{id}', [PerformerTransportPhotoController::class, 'destroy']);

Route::get('rental-statuses', [RentalStatusController::class, 'index']);
Route::post('rental-statuses', [RentalStatusController::class, 'store']);
Route::get('rental-statuses/{id}', [RentalStatusController::class, 'show']);
Route::put('rental-statuses/{id}', [RentalStatusController::class, 'update']);
Route::delete('rental-statuses/{id}', [RentalStatusController::class, 'destroy']);

Route::get('rentals', [RentalController::class, 'index']);
Route::post('rentals', [RentalController::class, 'store']);
Route::get('rentals/{id}', [RentalController::class, 'show']);
Route::put('rentals/{id}', [RentalController::class, 'update']);
Route::delete('rentals/{id}', [RentalController::class, 'destroy']);

Route::get('rental-applications', [RentalApplicationController::class, 'index']);
Route::post('rental-applications', [RentalApplicationController::class, 'store']);
Route::get('rental-applications/{id}', [RentalApplicationController::class, 'show']);
Route::put('rental-applications/{id}', [RentalApplicationController::class, 'update']);
Route::delete('rental-applications/{id}', [RentalApplicationController::class, 'destroy']);
