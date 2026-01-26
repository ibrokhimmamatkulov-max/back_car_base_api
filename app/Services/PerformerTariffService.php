<?php

// namespace App\Services;

// use App\Models\BodyType;
// use App\Models\CarOption;
// use App\Models\CategoryTariff;
// use App\Models\DriverCar;
// use App\Models\Marka;
// use App\Models\Performer;
// use App\Models\PerformerOption;
// use App\Models\PerformerTariff;
// use App\Models\PerformerTransport;
// use App\Models\Tariff;
// use App\Models\Car\ClassCar;
// use App\Models\TariffClassCar;
// use App\Models\TariffClassCarAdditionally;
// use App\Models\TariffClassCarExcludedModelCar;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Redis;

// class PerformerTariffService
// {
//     // public function set_tariff($performer_id)
//     // {
//     //     return $this->set_tariff_v2($performer_id);
//     // }

//     // public function set_tariff_by_class_car($tariff_class_car_id)
//     // {
//     //     $dbConnection = DB::connection('mysql_performer');
//     //     $tariff_class_car = $dbConnection->table('tariff_class_cars')->select('class_car_id')->where('id',$tariff_class_car_id)->first();
//     //     $model_cars = $dbConnection->table('model_cars')
//     //                             ->select('id')
//     //                             ->where('class_car_id', $tariff_class_car->class_car_id)
//     //                             ->pluck('id');
//     //     $performerTransports = $dbConnection->table('performer_transports')->select('id')->whereIn('car_model_id', $model_cars)->where('active',1)->pluck('id');
//     //     $drivers = $dbConnection->table('driver_cars')->select('driver_id')->whereIn('car_id', $performerTransports)->pluck('driver_id');
//     //     foreach ($drivers as $driver_id) {
//     //         $this->set_tariff_v2($driver_id);
//     //         Log::channel('classCarSettings')->info('DriverId: '.$driver_id);
//     //     }
//     // }

//     public function set_tariff_by_car($car_id)
//     {
//         try {
//             $car = PerformerTransport::find($car_id);
//             if ($car) {
//                 $drivers = DriverCar::where('car_id', $car->id)->pluck('driver_id');
//                 foreach ($drivers as $driver_id) {
//                     $this->set_tariff_v2($driver_id);
//                 }
//                 $result = [
//                     "success" => true,
//                     "code" => 200,
//                     "message" => "ok"
//                 ];
//             } else {
//                 $result = [
//                     "success" => false,
//                     "code" => 422,
//                     "message" => "Error performer data or car data!"
//                 ];
//             }
//         } catch (\Exception $e) {
//             Log::build([
//                 'driver' => 'single',
//                 'path' => storage_path('logs/performer_tariff_service.log'),
//             ])->error($e->getMessage(), $e->getTrace());
//             $result = [
//                 "success" => false,
//                 "code" => 500,
//                 "message" => $e->getMessage()
//             ];
//         }
//         return $result;
//     }

//     // todo compare it with driver
//     public function set_tariff_v2($performer_id): array
//     {
//         try {
//             $performer = Performer::find($performer_id);
//             if (!$performer || $performer->sub_serv_type === 'courier_with_bike') {
//                 return [
//                     "success" => false,
//                     "code" => 422,
//                     "message" => 'Performer not found!'
//                 ];
//             }

//             $division_id = $performer->division_id;
//             $service_type = $performer->service_type;

//             $tariffQuery = Tariff::query()
//                 ->where('is_active', Tariff::ACTIVE)
//                 ->where('division_id', $division_id);

//             // if not taxi assign only specific type else assign all tariffs
//             if ($performer->service_type !== 'taxi') {
//                 $tariffQuery->whereHas('type_tariff', function ($query) use ($service_type) {
//                     $query->whereHas('category_tariff', function ($catQuery) use ($service_type) {
//                         $catQuery->where('type', $service_type);
//                     });
//                 });
//             }

//             $tariff_ids = $tariffQuery->pluck('id')->toArray();

//             if(count($tariff_ids) < 1) {
//                 return [
//                     "success" => false,
//                     "code" => 422,
//                     "message" => 'No matching tariffs found for this division!'
//                 ];
//             }

//             $car_ids = DriverCar::where('driver_id',$performer->id)->pluck('car_id');
//             $car = PerformerTransport::whereIn('id',$car_ids)->where('active', PerformerTransport::ACTIVE)->first();
//             if (!$car) {
//                 return [
//                     "success" => false,
//                     "code" => 422,
//                     "message" => 'Car model excluded in class car!'
//                 ];
//             }

//             $car_conditions = $car?->condition?->level ?? 0;
//             $car_birth = date('Y') - $car->year_of_issue;

//             $width = 0;
//             $height = 0;
//             $length = 0;
//             $carrying_capacity = 0;
//             if(!is_null($car->cargo_properties)) {
//                 $cargo = json_decode($car->cargo_properties, true);
//                 $width = $cargo["width"] ?? 0;
//                 $height = $cargo["height"] ?? 0;
//                 $length = $cargo["length"] ?? 0;
//                 $carrying_capacity = $cargo["carrying_capacity"] ?? 0;
//             }

//             // Performer and car dop options
//             $performer_option = $performer?->options()->whereNotNull('performer_option_id')->pluck('performer_option_id') ?? [];
//             $car_option = $car?->car_options()->whereNotNull('option_id')->pluck('option_id') ?? [];
//             $car_body_type_id = $car?->body_type_id;

//             $tariffClassCar = TariffClassCar::with(['dop_conditions', 'not_dop_conditions'])
//                 ->whereIn('tariff_id', $tariff_ids)
//                 ->where('class_car_id', $car->model_car->class_car_id)
//                 ->where('age_up_to', '>=', $car_birth)
//                 ->where('min_count_seat', '<=', $car->count_seat)
//                 ->where('width', '<=', $width)
//                 ->where('height', '<=', $height)
//                 ->where('length', '<=', $length)
//                 ->where('carrying_capacity', '<=', $carrying_capacity)
//                 ->whereHas('car_condition', function ($query) use ($car_conditions) {
//                     $query->where('level', '<=', $car_conditions);
//                 })
//                 ->get();

//             $performer_tariffs = array();
//             foreach ($tariffClassCar as $item) {
//                 $not_models = TariffClassCarExcludedModelCar::where('model_car_id',$car->car_model_id)->where('tariff_class_car_id',$item->id)->get();
//                 if(count($not_models) > 0) {
//                     continue;
//                 }
//                 $check_car_options = TariffClassCarAdditionally::where('tariff_class_car_id', $item->id)->exists();
//                 if ($check_car_options) {

//                     $car_tariff_dop_options = $item->dop_conditions->pluck('id')->toArray();
//                     $car_tariff_not_dop_options = $item->not_dop_conditions->pluck('id')->toArray();
                    
//                     if (count($car_tariff_dop_options) > 0) {
//                         $car_dop_option = TariffClassCarAdditionally::query()
//                                 ->where('status', 1)
//                                 ->where(function ($query) use ($performer_option, $car_option, $car_body_type_id) {
//                                     $query->where(function ($query) use ($car_option) {
//                                         $query->where('model', CarOption::class)
//                                             ->whereIn('model_id', $car_option);
//                                     })
//                                         ->orWhere(function ($query) use ($performer_option) {
//                                             $query->where('model', PerformerOption::class)
//                                                 ->whereIn('model_id', $performer_option);
//                                         })
//                                         ->orWhere(function ($query) use ($car_body_type_id) {
//                                             $query->where('model', BodyType::class)
//                                                 ->where('model_id', $car_body_type_id);
//                                         });
//                                 })
//                                 ->whereIn('id', $car_tariff_dop_options)
//                                 ->exists();

//                         if(!$car_dop_option) {
//                             continue;
//                         }
//                     }
                    
//                     if (count($car_tariff_not_dop_options) > 0) {
//                         $car_not_dop_option = TariffClassCarAdditionally::query()
//                                 ->where('status', 0)
//                                 ->where(function ($query) use ($performer_option, $car_option, $car_body_type_id) {
//                                         $query->where(function ($query) use ($car_option) {
//                                             $query->where('model', CarOption::class)
//                                                 ->whereIn('model_id', $car_option);
//                                         })
//                                         ->orWhere(function ($query) use ($performer_option) {
//                                             $query->where('model', PerformerOption::class)
//                                                 ->whereIn('model_id', $performer_option);
//                                         })
//                                         ->orWhere(function ($query) use ($car_body_type_id) {
//                                             $query->where('model', BodyType::class)
//                                                 ->where('model_id', $car_body_type_id);
//                                         });
//                                 })
//                                 ->whereIn('id', $car_tariff_not_dop_options)
//                                 ->exists();
                       
//                         if($car_not_dop_option) {
//                             continue;
//                         }
//                     }
                    
//                 }
//                 $data = [
//                     'performer_id' => $performer->id,
//                     'tariff_id' => $item->tariff_id,
//                     'tariff_class_car_id' => $item->id,
//                     'created_at' => Carbon::now(),
//                     'updated_at' => Carbon::now()
//                 ];
//                 $performer_tariffs[] = $data;
//             }

//             PerformerTariff::query()->where('performer_id', $performer->id)->delete();
//             if (count($performer_tariffs) > 0) {
//                 PerformerTariff::query()->insert($performer_tariffs);
//                 $tariffIds = array_column($performer_tariffs, 'tariff_id');
//                 Redis::connection('cache')->set('performer_tariffs:'.$performer->id, json_encode($tariffIds));
//             }
//             Artisan::call("push-performer-data:to-realtime", ['performer_id' => $performer->id]);
//             Artisan::call("send:performer-division", ['performer_id' => $performer->id]);
//             return [
//                 "success" => true,
//                 "code" => 200,
//                 "message" => 'ok'
//             ];
//         } catch (\Exception $e) {
//             Log::build([
//                 'driver' => 'single',
//                 'path' => storage_path('logs/performer_tariff_service.log'),
//             ])->error($e->getMessage(), $e->getTrace());
//             return [
//                 "success" => false,
//                 "code" => 500,
//                 "message" => $e->getMessage()
//             ];
//         }
//     }

//     // public function performerData($performer_id)
//     // {

//     // }
// }
