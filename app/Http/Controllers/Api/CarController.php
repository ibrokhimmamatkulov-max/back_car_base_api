<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Cars\CarResource;
use App\Http\Resources\Cars\CarResourceCollection;
use App\Models\CarOption;
use App\Models\CategoryCar;
use App\Models\ColorCar;
use App\Models\PerformerTransportOption;
use App\Models\RentalTariff;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\PerformerTransport;
use App\Models\PerformerTransportPhoto;
use App\Http\Controllers\Controller;
use App\Models\BodyType;
use App\Models\CarCondition;
use App\Models\Marka;
use App\Services\CarFilterService;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function fuel_types()
    {
        $fuel_types = CarOption::where('model', 'car_fuel_type')->where('is_active', 1)->get(['id', 'name']);
        return $this->success($fuel_types);
    }

    public function car_dop_options(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_car_id' => ['required', 'integer', Rule::exists(CategoryCar::class, 'id')],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $car_dop_options = CarOption::where('is_active', 1)
            ->where('category_car_id', $request->category_car_id)
            ->get(['id', 'name']);

        return $this->success($car_dop_options);
    }

    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : config('sip-gram.limit_data');
        if ($limit > config('sip-gram.max_limit_data')) {
            $limit = config('sip-gram.max_limit_data');
        }

        $cars = PerformerTransport::query()->with([
            'model_car', 'car_connection', 'model_car.brand', 'model_car.category_car',
            'model_car.class_car', 'body_type', 'color', 'condition', 'updated_user',
            'dopOptions', 'dopOptions.car_option', 'fuel_type', 'photos', 'tariffs', 'city', 'gearbox',
        ]);

        $cars = CarFilterService::applyFilters($cars, $request);

        return $this->success(new CarResourceCollection($cars->orderByDesc('id')->limit($limit)->get()));
    }

    public function filter_journal_car()
    {
        return $this->success([
            ['filter_journal_car' => 'ALL_LIST',               'text' => 'Полный список'],
            ['filter_journal_car' => 'ACTIVE',                 'text' => 'Работают'],
            ['filter_journal_car' => 'DISMISSED',              'text' => 'Уволенные'],
            ['filter_journal_car' => 'CREATED_BY_ME',          'text' => 'Созданные мной'],
            ['filter_journal_car' => 'INSPECTION_IN_THE_OFFICE','text' => 'Осмотр в офисе'],
            ['filter_journal_car' => 'DOUBLES',                'text' => 'Дубли'],
        ]);
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
                Rule::unique(PerformerTransport::class, 'car_number'),
            ],
            'count_seat'       => 'required|integer' . $rule,
            'fuel_type_id'     => ['required', Rule::exists(CarOption::class, 'id')->where('model', 'car_fuel_type')->where('is_active', 1)],
            'cargo_properties' => 'nullable|string',
            'dop_info'         => 'nullable|string',
            'dop_options'      => 'nullable|array',
            'dop_options.*.car_option_id' => ['required', Rule::exists(CarOption::class, 'id')],
            'city_id'       => ['required', Rule::exists('cities', 'id')],
            'gearbox_id'    => ['required', Rule::exists('gearboxes', 'id')],
            'min_rent_days' => ['required', 'integer', 'min:1'],
            'address'       => ['required', 'string', 'max:255'],
            'tariffs'       => 'nullable|array',
            'tariffs.*'     => ['integer', Rule::exists(RentalTariff::class, 'id')],
            'photos'        => ['nullable', 'array', 'max:4'],
            'photos.*'      => ['file', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $dop_options = $request->dop_options;
        if (is_array($dop_options)) {
            foreach ($dop_options as $option) {
                $v = Validator::make($option, [
                    'car_option_id' => ['required', Rule::exists(CarOption::class, 'id')],
                    'is_check'      => ['boolean'],
                ]);
                if ($v->fails()) {
                    return $this->error('Validation error', 422, $v->errors());
                }
            }
        }

        $cargo_properties = json_decode($request->cargo_properties);
        if ((int)$request->category_car_id === (int)PerformerTransport::CARGO) {
            $v = Validator::make($request->all(), ['cargo_properties' => 'required|string']);
            if ($v->fails()) {
                return $this->error('Validation error', 422, $v->errors());
            }
            $v2 = Validator::make((array)$cargo_properties, [
                'width'             => 'integer',
                'height'            => 'integer',
                'length'            => 'integer',
                'carrying_capacity' => 'required|integer',
            ]);
            if ($v2->fails()) {
                return $this->error('Validation error', 422, $v2->errors());
            }
        } else {
            if (isset($request->cargo_properties) && (int)$request->category_car_id !== (int)PerformerTransport::CARGO) {
                return $this->error('Необходимо выбрать категорию для грузового типа', 422);
            }
        }

        $existing = PerformerTransport::where('active', 1)->where('car_number', $request->car_number)->first();
        if ($existing) {
            return $this->success(['route' => route('cars.edit', $existing->id)], 'Автомобиль уже существует');
        }

        $user_id = auth()->id();
        $car = new PerformerTransport;
        $car->car_model_id      = $request->model_car_id;
        $car->color_id          = $request->color_id;
        $car->body_type_id      = $request->body_type_id ?? null;
        $car->condition_id      = $request->condition_id;
        $car->year_of_issue     = $request->year_of_issue;
        $car->car_number        = $request->car_number;
        $car->count_seat        = $request->count_seat;
        $car->dop_info          = $request->dop_info;
        $car->cargo_properties  = isset($cargo_properties) ? json_encode($cargo_properties) : null;
        $car->connected_id      = PerformerTransport::WAITING_CONNECTION;
        $car->created_user_id   = $user_id;
        $car->city_id           = $request->city_id;
        $car->gearbox_id        = $request->gearbox_id;
        $car->min_rent_days     = $request->min_rent_days;
        $car->address           = $request->address;
        $car->save();

        if ($request->has('fuel_type_id') || !is_null($request->fuel_type_id)) {
            $car->fuel_type_id = $request->fuel_type_id;
            $car->save();
            PerformerTransportOption::updateOrCreate(
                ['performer_transport_id' => $car->id, 'option_id' => $car->fuel_type_id],
                ['performer_transport_id' => $car->id, 'option_id' => $car->fuel_type_id, 'is_check' => 1]
            );
        }

        if (is_array($dop_options)) {
            foreach ($dop_options as $option) {
                $option = (array)$option;
                PerformerTransportOption::updateOrCreate(
                    ['performer_transport_id' => $car->id, 'option_id' => $option['car_option_id'], 'is_check' => $option['is_check'] ?? 0],
                    ['performer_transport_id' => $car->id, 'option_id' => $option['car_option_id'], 'is_check' => $option['is_check'] ?? 0]
                );
            }
        }

        $tariffs = $request->tariffs;
        if (is_array($tariffs)) {
            $car->tariffs()->sync($tariffs);
        }

        foreach ($request->file('photos', []) as $photo) {
            PerformerTransportPhoto::create([
                'performer_transport_id' => $car->id,
                'path' => $photo->store('cars', 'public'),
            ]);
        }

        return $this->success(['car_id' => $car->id], 'Автомобиль успешно добавлен!', 201);
    }

    public function edit($id)
    {
        $car = PerformerTransport::find($id);
        if (!$car) {
            return $this->error('Транспорт не найден!', 404);
        }
        return $this->success(new CarResource($car));
    }

    public function update(Request $request, $id)
    {
        $car = PerformerTransport::find($id);
        if (!$car) {
            return $this->error('Транспорт не найден!', 404);
        }

        $model_car = Marka::find($request->model_car_id);
        $rule = '';
        if ($model_car) {
            $min_seat = $model_car->car_seat_from ?? 1;
            $max_seat = $model_car->car_seat_before ?? 100;
            $rule .= '|between:' . $min_seat . ',' . $max_seat;
        }

        $validator = Validator::make($request->all(), [
            'category_car_id'  => ['required', Rule::exists(CategoryCar::class, 'id')],
            'model_car_id'     => ['required', Rule::exists(Marka::class, 'id')->where('category_car_id', $request->category_car_id)],
            'body_type_id'     => ['nullable', Rule::exists(BodyType::class, 'id')->where('category_car_id', $request->category_car_id)],
            'color_id'         => ['required', Rule::exists(ColorCar::class, 'id')],
            'year_of_issue'    => 'required|integer|min:1980|max:' . date('Y'),
            'condition_id'     => ['required', Rule::exists(CarCondition::class, 'id')],
            'car_number'       => ['required', 'regex:/([0-9]{3,4}[A-Z]{2,11}(?:01|02|03|04|05|06|07|08|10|))|[A-Z][0-9]{3,4}[A-Z]{2,10}(?:01|02|03|04|05|06|07|08|10|)/', 'max:8', Rule::unique(PerformerTransport::class, 'car_number')->ignore($car->id, 'id')],
            'count_seat'       => 'required|integer' . $rule,
            'fuel_type_id'     => ['nullable', Rule::exists(CarOption::class, 'id')->where('model', 'car_fuel_type')->where('is_active', 1)],
            'cargo_properties' => 'nullable|string',
            'dop_info'         => 'nullable|string',
            'dop_options'      => 'nullable|array',
            'dop_options.*.car_option_id' => ['required', Rule::exists(CarOption::class, 'id')],
            'city_id'       => ['required', Rule::exists('cities', 'id')],
            'gearbox_id'    => ['required', Rule::exists('gearboxes', 'id')],
            'min_rent_days' => ['required', 'integer', 'min:1'],
            'address'       => ['required', 'string', 'max:255'],
            'tariffs'       => 'nullable|array',
            'tariffs.*'     => ['integer', Rule::exists(RentalTariff::class, 'id')],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $dop_options = $request->dop_options;
        if (is_string($dop_options)) {
            $decoded = json_decode($dop_options, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $dop_options = $decoded;
            }
        }

        $tariffs = $request->tariffs;
        if (is_string($tariffs)) {
            $decoded = json_decode($tariffs, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $tariffs = $decoded;
            }
        }

        if (is_array($dop_options)) {
            foreach ($dop_options as $option) {
                Log::info('Option:', $option);
                $v = Validator::make($option, [
                    'car_option_id' => ['required', Rule::exists(CarOption::class, 'id')],
                    'is_check'      => ['boolean'],
                ]);
                if ($v->fails()) {
                    return $this->error('Validation error', 422, $v->errors());
                }
            }
        }

        $cargo = null;
        if (isset($request->cargo_properties)) {
            $cargo_properties = json_decode($request->cargo_properties);
            if ((int)$request->category_car_id === (int)PerformerTransport::CARGO) {
                $v = Validator::make($request->all(), ['cargo_properties' => 'required|string']);
                if ($v->fails()) {
                    return $this->error('Validation error', 422, $v->errors());
                }
                $v2 = Validator::make((array)$cargo_properties, [
                    'width'             => 'integer',
                    'height'            => 'integer',
                    'length'            => 'integer',
                    'carrying_capacity' => 'required|integer',
                ]);
                if ($v2->fails()) {
                    return $this->error('Validation error', 422, $v2->errors());
                }
                $cargo = $request->cargo_properties;
            } else {
                if (isset($request->cargo_properties) && (int)$request->category_car_id !== (int)PerformerTransport::CARGO) {
                    return $this->error('Необходимо выбрать категорию для грузового типа', 422);
                }
            }
        }

        $car->car_model_id  = $request->model_car_id;
        $car->body_type_id  = $request->body_type_id ?? null;
        $car->count_seat    = $request->count_seat;
        $car->color_id      = $request->color_id;
        $car->condition_id  = $request->condition_id;
        $car->year_of_issue = $request->year_of_issue;
        $car->car_number    = $request->car_number;
        $car->cargo_properties = $cargo;
        $car->dop_info      = $request->dop_info;
        $car->updated_user_id = auth()->id();
        $car->city_id       = $request->city_id;
        $car->gearbox_id    = $request->gearbox_id;
        $car->min_rent_days = $request->min_rent_days;
        $car->address       = $request->address;
        $car->update();

        PerformerTransportOption::query()->where('performer_transport_id', $car->id)->delete();

        if ($request->has('fuel_type_id') || !is_null($request->fuel_type_id)) {
            $car->fuel_type_id = $request->fuel_type_id;
            $car->save();
            PerformerTransportOption::updateOrCreate(
                ['performer_transport_id' => $car->id, 'option_id' => $request->fuel_type_id],
                ['performer_transport_id' => $car->id, 'option_id' => $request->fuel_type_id, 'is_check' => 1]
            );
        }

        if (is_array($dop_options)) {
            foreach ($dop_options as $item) {
                PerformerTransportOption::updateOrCreate(
                    ['performer_transport_id' => $car->id, 'option_id' => $item['car_option_id']],
                    ['is_check' => 1]
                );
            }
        }

        if (is_array($tariffs)) {
            $car->tariffs()->sync($tariffs);
        }

        return $this->success(['car_id' => $car->id], 'Автомобиль успешно изменён!');
    }
}
