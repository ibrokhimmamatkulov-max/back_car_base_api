<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Cars\CarResource;
use App\Http\Resources\Cars\CarResourceCollection;
use App\Models\CarOption;
use App\Models\CategoryCar;
use App\Models\ColorCar;
use App\Models\Division;
use App\Models\PerformerTransportOption;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\PerformerTransport;
use App\Http\Controllers\Controller;
use App\Models\BodyType;
use App\Models\CarCondition;
use App\Models\CarPark;
use App\Models\Marka;
use App\Services\CarFilterService;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function fuel_types()
    {
        $fuel_types = CarOption::where('model','car_fuel_type')->where('is_active', 1)->get(['id', 'name']);
        return response()->json($fuel_types);
    }

    public function car_dop_options(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_car_id' => ['required', 'integer', Rule::exists(CategoryCar::class, 'id')]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $car_dop_options = CarOption::where('is_active', 1)->where('category_car_id', $request->category_car_id)->get(['id', 'name']);
        return response()->json($car_dop_options);
    }

    public function index(Request $request)
    {
        // $availableCarParkIds = auth()->user()?->employee->taxiParks->pluck('id')->toArray();

        // Filter limit
        $limit = $request->has('limit') ? $request->limit : config('sip-gram.limit_data');
        if ($limit > config('sip-gram.max_limit_data')) {
            $limit = config('sip-gram.max_limit_data');
        }

        $cars = PerformerTransport::query()->with([
            'division',
            'model_car',
            'car_connection',
            'model_car.brand',
            'model_car.category_car',
            'model_car.class_car',
            'body_type',
            'color',
            'condition',
            // 'car_drivers',
            // 'created_user.employee',
            'updated_user',
            'dopOptions',
            'dopOptions.car_option',
            // 'histories',
            'fuel_type',
            'carPark',
            'photos',
            'tariffs',
            'city',
            'gearbox'
        ])->whereIn('division_id', [1])
            // ->when(!empty($availableCarParkIds), function ($query) use ($availableCarParkIds) {
            //     $query->whereIn('car_park_id', $availableCarParkIds);
            // })
            ;

       $cars=CarFilterService::applyFilters($cars,$request);

        return new CarResourceCollection($cars->orderByDesc('id')->limit($limit)->get());
    }
    
    public function filter_journal_car()
    {
        $filter_journal_car = [
            [
                'filter_journal_car' => 'ALL_LIST',
                'text'               => 'Полный список'
            ],
            [
                'filter_journal_car' => 'ACTIVE',
                'text'               => 'Работают'
            ],
            [
                'filter_journal_car' => 'DISMISSED',
                'text'               => 'Уволенные'
            ],
            [
                'filter_journal_car' => 'CREATED_BY_ME',
                'text'               => 'Созданные мной'
            ],
            [
                'filter_journal_car' => 'INSPECTION_IN_THE_OFFICE',
                'text'               => 'Осмотр в офисе'
            ],
            [
                'filter_journal_car' => 'DOUBLES',
                'text'               => 'Дубли'
            ],

        ];

        return response()->json($filter_journal_car);
    }

    public function store(Request $request)
    {
        $model_car = Marka::find($request->model_car_id);
        $rule = '';
        if ($model_car) {
            $min_seat = $model_car->car_seat_from ?? 1;
            $max_seat = $model_car->car_seat_before ?? 100;
            $rule .= '|between:' . $min_seat . ',' . $max_seat;
        }
        $validator = Validator::make($request->all(), [
            'division_id'      => ['required', Rule::exists(Division::class, 'id')],
            'category_car_id'  => ['required', Rule::exists(CategoryCar::class, 'id')],
            'model_car_id'     => ['required', Rule::exists(Marka::class, 'id')->where('category_car_id', $request->category_car_id)],
            'body_type_id'     => ['required', Rule::exists(BodyType::class, 'id')->where('category_car_id', $request->category_car_id)],
            'color_id'         => ['required', Rule::exists(ColorCar::class, 'id')],
            'year_of_issue'    => 'required|integer|min:1980|max:' . date('Y'),
            'condition_id'     => ['required', Rule::exists(CarCondition::class, 'id')],
            'car_number'       => [
                Rule::requiredIf($request->category_car_id != CategoryCar::MOTORBIKE),
                $request->category_car_id == CategoryCar::MOTORBIKE
                    ? 'regex:/^[0-9]{3}[A-Z]{1}[0-9]{2}$/'
                    : 'regex:/^[0-9]{3,4}[A-Z]{2}[0-9]{2}$/',
                'max:8',
                Rule::unique(PerformerTransport::class, 'car_number')],
            'count_seat'       => 'required|integer' . $rule,
            'fuel_type_id'     => ['required', Rule::exists(CarOption::class, 'id')->where('model','car_fuel_type')->where('is_active',1)],
            'cargo_properties' => 'nullable|string',
            'dop_info'         => 'nullable|string',
            'dop_options'      => 'nullable|array',
            'dop_options.*.car_option_id' => ['required', Rule::exists(CarOption::class, 'id')],
            'car_park_id' => [
                Rule::requiredIf($request->division_id == 6),
                Rule::exists(CarPark::class, 'id'),
            ],
            'city_id'        => ['required', Rule::exists('cities', 'id')],
            'gearbox_id'     => ['required', Rule::exists('gearboxes', 'id')],
            'min_rent_days'  => ['required', 'integer', 'min:1'],
            'address'        => ['required', 'string', 'max:255'],

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $dop_options = $request->dop_options;
        if (is_array($dop_options)) {
            foreach ($dop_options as $option) {
                $validator = Validator::make($option, [
                    'car_option_id' => ['required', Rule::exists(CarOption::class, 'id')],
                    'is_check'      => ['boolean']
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }
            }
        }

        $cargo_properties = json_decode($request->cargo_properties);
        if ((int)$request->category_car_id === (int)PerformerTransport::CARGO) {
            $validator = Validator::make($request->all(), [
                'cargo_properties' => 'required|string'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
            $validator = Validator::make((array)$cargo_properties, [
                'width'             => 'integer',
                'height'            => 'integer',
                'length'            => 'integer',
                'carrying_capacity' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
        } else {
            if (isset($request->cargo_properties) && (int)$request->category_car_id !== (int)PerformerTransport::CARGO) {
                return response()->json([
                    "message" => "Не обходимо выбрать категорию для грузового типа"
                ], 422);
            }
        }
        $car = PerformerTransport::where('active', 1)->where('car_number', $request->car_number)->first();
        if ($car) {
            return response()->json([
                'message' => 'Find car by car number!',
                'route'   => route('cars.edit', $car->id)
            ]);
        }
        $user_id = auth()->id();
        $car = new PerformerTransport;
        $car->division_id = $request->division_id;
        $car->car_model_id = $request->model_car_id;
        $car->color_id = $request->color_id;
        $car->body_type_id = $request->body_type_id ?? null;
        $car->condition_id = $request->condition_id;
        $car->year_of_issue = $request->year_of_issue;
        $car->car_number = $request->car_number;
        $car->count_seat = $request->count_seat;
        $car->dop_info = $request->dop_info;
        $car->cargo_properties = isset($cargo_properties) ? json_encode($cargo_properties) : null;
        $car->connected_id = PerformerTransport::WAITING_CONNECTION;
        $car->created_user_id = $user_id;
        $car->car_park_id = $request->car_park_id;
        $car->city_id = $request->city_id;
        $car->gearbox_id = $request->gearbox_id;
        $car->min_rent_days = $request->min_rent_days;
        $car->address = $request->address;
        $car->save();

        if($request->has('fuel_type_id') || !is_null($request->fuel_type_id)) {
            $car->fuel_type_id = $request->fuel_type_id;
            $car->save();
            PerformerTransportOption::updateOrCreate([
                'performer_transport_id' => $car->id,
                'option_id'              => $car->fuel_type_id,
                'is_check'               => 1
            ], [
                'performer_transport_id' => $car->id,
                'option_id'              => $car->fuel_type_id,
                'is_check'               => 1
            ]);
        }

        if (is_array($dop_options)) {
            foreach ($dop_options as $option) {
                $option = (array)$option;
                PerformerTransportOption::updateOrCreate([
                    'performer_transport_id' => $car->id,
                    'option_id'              => $option['car_option_id'],
                    'is_check'               => $option['is_check'] ?? 0
                ], [
                    'performer_transport_id' => $car->id,
                    'option_id'              => $option['car_option_id'],
                    'is_check'               => $option['is_check'] ?? 0
                ]);
            }
        }

        return response()->json([
            'message' => 'Автомобиль успешно добавлен!',
            'car_id'  => $car->id
        ]);
    }
   
    public function edit($id)
    {
        $car = PerformerTransport::find($id);
        if ($car) {
            $car = new CarResource($car);
        }
        return response()->json($car);
    }

    public function update(Request $request, $id)
    {
        $car = PerformerTransport::find($id);
        if ($car) {
            $model_car = Marka::find($request->model_car_id);
            $rule = '';
            if ($model_car) {
                $min_seat = $model_car->car_seat_from ?? 1;
                $max_seat = $model_car->car_seat_before ?? 100;
                $rule .= '|between:' . $min_seat . ',' . $max_seat;
            }
            $validator = Validator::make($request->all(), [
                'division_id'      => ['required', Rule::exists(Division::class, 'id')],
                'category_car_id'  => ['required', Rule::exists(CategoryCar::class, 'id')],
                'model_car_id'     => ['required', Rule::exists(Marka::class, 'id')->where('category_car_id', $request->category_car_id)],
                'body_type_id'     => ['nullable', Rule::exists(BodyType::class, 'id')->where('category_car_id', $request->category_car_id)],
                'color_id'         => ['required', Rule::exists(ColorCar::class, 'id')],
                'year_of_issue'    => 'required|integer|min:1980|max:' . date('Y'),
                'condition_id'     => ['required', Rule::exists(CarCondition::class, 'id')],
                'car_number'       => ['required', 'regex:/([0-9]{3,4}[A-Z]{2,11}(?:01|02|03|04|05|06|07|08|10|))|[A-Z][0-9]{3,4}[A-Z]{2,10}(?:01|02|03|04|05|06|07|08|10|)/', 'max:8', Rule::unique(PerformerTransport::class, 'car_number')->ignore($car->id,'id')],
                'count_seat'       => 'required|integer' . $rule,
                'fuel_type_id'     => ['nullable', Rule::exists(CarOption::class, 'id')->where('model','car_fuel_type')->where('is_active',1)],
                'cargo_properties' => 'nullable|string',
                'dop_info'         => 'nullable|string',
                'dop_options' => 'nullable|array',
                'dop_options.*.car_option_id' => ['required', Rule::exists(CarOption::class, 'id')],
                'car_park_id' => [
                    Rule::requiredIf($request->division_id == 6),
                    Rule::exists(CarPark::class, 'id'),
                ],
                'city_id'        => ['required', Rule::exists('cities', 'id')],
                'gearbox_id'     => ['required', Rule::exists('gearboxes', 'id')],
                'min_rent_days'  => ['required', 'integer', 'min:1'],
                'address'        => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            $dop_options = $request->dop_options;
            if (is_string($dop_options)) {
                $decoded = json_decode($dop_options, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $dop_options = $decoded;
                }
            }

            if (is_array($dop_options)) {
                foreach ($dop_options as $option) {
                    Log::info('Option:', $option);
                    $validator = Validator::make($option, [
                        'car_option_id' => ['required', Rule::exists(CarOption::class, 'id')],
                        'is_check'      => ['boolean']
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'errors' => $validator->errors()
                        ], 422);
                    }
                }
            }
            $cargo = null;
            if(isset($request->cargo_properties)) {
                $cargo_properties = json_decode($request->cargo_properties);
                if ((int)$request->category_car_id === (int)PerformerTransport::CARGO) {
                    $validator = Validator::make($request->all(), [
                        'cargo_properties' => 'required|string'
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            'errors' => $validator->errors()
                        ], 422);
                    }
                    $validator = Validator::make((array)$cargo_properties, [
                        'width'             => 'integer',
                        'height'            => 'integer',
                        'length'            => 'integer',
                        'carrying_capacity' => 'required|integer',
                    ]);
    
                    if ($validator->fails()) {
                        return response()->json([
                            'errors' => $validator->errors()
                        ], 422);
                    }
                    $cargo = $request->cargo_properties;
                } else {
                    if (isset($request->cargo_properties) && (int)$request->category_car_id !== (int)PerformerTransport::CARGO) {
                        return response()->json([
                            "message" => "Не обходимо выбрать категорию для грузового типа"
                        ], 422);
                    }
                }
            }

            $user_id = auth()->id();
            //  $user = User::findOrFail($user_id);

            // $history_service = new PerformerTransportHistoryService();
            // $history_performer_transport = $history_service->forUpdate($car, 'users', $user_id);
            //            $dop_info = $car->dop_info;
            //            if ($request->has('dop_info')) {
            //                $dop_info .= $request->dop_info;
            //            }
            //
            //            if(strlen($dop_info) > 500) {
            //                $dop_info = substr($dop_info, strpos($dop_info, "\n\r",300)+5, strlen($dop_info)-1);
            //            }
            //
            //            $dop_info .= Carbon::now().' ID:'.$user_id.' Name:'.$user->first_name.' '.$user->last_name.'\n\r';

            $car->division_id = $request->division_id;
            $car->car_model_id = $request->model_car_id;
            $car->body_type_id = $request->body_type_id ?? null;
            $car->count_seat = $request->count_seat;
            $car->color_id = $request->color_id;
            $car->condition_id = $request->condition_id;
            $car->year_of_issue = $request->year_of_issue;
            $car->car_number = $request->car_number;
            $car->cargo_properties = $cargo;
            $car->dop_info = $request->dop_info;
            $car->updated_user_id = $user_id;
            //$car->created_user_id = $car->created_user_id;
            $car->car_park_id = $request->car_park_id;
            $car->city_id = $request->city_id;
            $car->gearbox_id = $request->gearbox_id;
            $car->min_rent_days = $request->min_rent_days;
            $car->address = $request->address;
            $car->update();
            PerformerTransportOption::query()->where('performer_transport_id', $car->id)->delete();

            if($request->has('fuel_type_id') || !is_null($request->fuel_type_id)) {
                $car->fuel_type_id = $request->fuel_type_id;
                $car->save();
                PerformerTransportOption::updateOrCreate([
                    'performer_transport_id' => $car->id,
                    'option_id'              => $request->fuel_type_id,
                    'is_check'               => 1
                ], [
                    'performer_transport_id' => $car->id,
                    'option_id'              => $request->fuel_type_id,
                    'is_check'               => 1
                ]);
            }

            if (is_array($dop_options)) {
                foreach ($dop_options as $item) {
                    PerformerTransportOption::updateOrCreate(
                        [
                            'performer_transport_id' => $car->id,
                            'option_id'              => $item['car_option_id'],
                        ],
                        [
                            'is_check' => 1,
                        ]
                    );
                }
            }
            return response()->json([
                'message' => 'Автомобиль успешно изменён!',
                'car_id'  => $car->id
            ]);
        } else {
            return response()->json([
                'message' => 'Такой ID транспорт не найден!'
            ], 422);
        }
    }
}