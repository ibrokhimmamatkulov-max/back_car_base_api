<?php

use App\Http\Controllers\Api\ApplicationStatusController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarBodyTypeController;
use App\Http\Controllers\Api\CarBrandController;
use App\Http\Controllers\Api\CarCategoryController;
use App\Http\Controllers\Api\CarClassController;
use App\Http\Controllers\Api\CarConditionController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CarModelController;
use App\Http\Controllers\Api\CarOptionController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\LandingController;
use App\Http\Controllers\Api\ColorCarController;
use App\Http\Controllers\Api\GearboxController;
use App\Http\Controllers\Api\PerformerTransportPhotoController;
use App\Http\Controllers\Api\RentalApplicationController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\RentalStatusController;
use App\Http\Controllers\Api\RentalTariffController;
use App\Http\Controllers\Api\RoleController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->group(function (){

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

    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::patch('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

    Route::get('/car-settings/categories', [CarCategoryController::class, 'index']);
    Route::post('/car-settings/categories', [CarCategoryController::class, 'store']);
    Route::get('/car-settings/categories/{category_car_id}/edit', [CarCategoryController::class, 'show']);
    Route::patch('/car-settings/categories/{category_car_id}', [CarCategoryController::class, 'update']);

    Route::get('/car-settings/model-cars', [CarModelController::class, 'index']);
    Route::post('/car-settings/model-cars', [CarModelController::class, 'store']);
    Route::post('/car-settings/model-cars/data', [CarModelController::class, 'data']);
    Route::get('/car-settings/model-cars/{car_model_id}/edit', [CarModelController::class, 'edit']);
    Route::patch('/car-settings/model-cars/{car_model_id}', [CarModelController::class, 'update']);

    Route::get('/car-settings/brands', [CarBrandController::class, 'index']);
    Route::get('/car-settings/brands/{brand_id}/edit', [CarBrandController::class, 'edit']);
    Route::post('/car-settings/brands', [CarBrandController::class, 'store']);
    Route::patch('/car-settings/brands/{brand_id}', [CarBrandController::class, 'update']);

    Route::get('/car-settings/classes', [CarClassController::class, 'index']);
    Route::post('/car-settings/classes', [CarClassController::class, 'store']);
    Route::get('/car-settings/classes/{class_car_id}/edit', [CarClassController::class, 'edit']);
    Route::patch('/car-settings/classes/{class_car_id}', [CarClassController::class, 'update']);

    Route::get('/car-settings/body-types', [CarBodyTypeController::class, 'index']);
    Route::post('/car-settings/body-types', [CarBodyTypeController::class, 'store']);
    Route::get('/car-settings/body-types/{body_type_id}/edit', [CarBodyTypeController::class, 'edit']);
    Route::patch('/car-settings/body-types/{body_type_id}', [CarBodyTypeController::class, 'update']);

    Route::get('/car-settings/car-colors', [ColorCarController::class, 'index']);
    Route::post('/car-settings/car-colors', [ColorCarController::class, 'store']);
    Route::get('/car-settings/car-colors/{color_id}/edit', [ColorCarController::class, 'edit']);
    Route::patch('/car-settings/car-colors/{color_id}', [ColorCarController::class, 'update']);

    Route::get('/car-settings/car-conditions', [CarConditionController::class, 'index']);
    Route::post('/car-settings/car-conditions', [CarConditionController::class, 'store']);
    Route::get('/car-settings/car-conditions/{car_condition}/edit', [CarConditionController::class, 'edit']);
    Route::patch('/car-settings/car-conditions/{car_condition}', [CarConditionController::class, 'update']);

    Route::get('/car-settings/dop-options', [CarOptionController::class, 'index']);
    Route::post('/car-settings/dop-options', [CarOptionController::class, 'store']);
    Route::get('/car-settings/dop-options/{option_id}/edit', [CarOptionController::class, 'edit']);
    Route::patch('/car-settings/dop-options/{option_id}', [CarOptionController::class, 'update']);
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:30,1');
});

// Public landing API — без авторизации
Route::prefix('landing')->group(function () {
    Route::get('cities',          [LandingController::class, 'cities']);
    Route::get('rental-tariffs', [LandingController::class, 'rentalTariffs']);
    Route::get('gearboxes',      [LandingController::class, 'gearboxes']);
    Route::get('fuel-types',     [LandingController::class, 'fuelTypes']);
    Route::get('offers',         [LandingController::class, 'offers']);
    Route::get('offers/{id}',    [LandingController::class, 'offer']);
    Route::post('apply',         [LandingController::class, 'apply'])->middleware('throttle:10,1');
});
