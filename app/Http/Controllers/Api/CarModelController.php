<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarModelRequest;
use App\Http\Resources\CarModelResource;
use App\Models\CarBrand;
use App\Models\CategoryCar;
use App\Models\Marka;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CarModelController extends Controller
{
    public function data(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_car_id' => ['required', Rule::exists(CategoryCar::class, 'id')],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
            $category = CategoryCar::find($request->category_car_id);
            $car_classes = [];
            if ($category) {
                $car_classes = $category->class_cars()->where('is_active', 1)->get(['category_car_id', 'id', 'name']);
            }
            $car_brands = CarBrand::where('is_active', 1)->get(['id', 'name']);
            return response()->json([
                'car_classes' => $car_classes,
                'car_brands' => $car_brands ?? [],
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Произошла системная ошибка!',
                'errors' => $exception->getMessage()
            ], 422);
        }
    }
   
    public function index(Request $request)
    {
        $carModels = Marka::query()->with(['category_car', 'brand', 'class_car', ]);
        $limit = 100;
        if ($request->has('limit')) {
            $limit = $request->limit;
        }
        if ($request->has('filter_is_active')) {
            $carModels->where('is_active', $request->filter_is_active);
        }
        if ($request->has('filter_id')) {
            $filter_id = $request->filter_id;
            if ($request->filter_id_codition == "equalOrMore") {
                $carModels->where('id', '>=', $filter_id);
            } elseif ($request->filter_id_condition == "equalOrLess") {
                $carModels->where('id', '>=', $filter_id);
            } elseif ($request->filter_id_condition == "less") {
                $carModels->where('id', '<', $filter_id);
            } elseif ($request->filter_id_condition == "more") {
                $carModels->where('id', '>', $filter_id);
            } else {
                $carModels->where('id', $filter_id);
            }
        }
        // filter by name
        if ($request->has('filter_name')) {
            $name = $request->filter_name;
            if ($request->filter_name_condition == "startLike") {
                $carModels->where('name', "Like", "$name%");
            } elseif ($request->filter_name_condition == "endLike") {
                $carModels->where('name', "Like", "%$name");
            } elseif ($request->filter_name_condition == "include") {
                $carModels->where('name', "Like", "%$name%");
            } else {
                $carModels->where('name', $name);
            }
        }
        if ($request->has('filter_category_car_id')) {
            $carModels->where('category_car_id', $request->filter_category_car_id);
        }
        if ($request->has('filter_car_brand_id')) {
            $carModels->where('car_brand_id', $request->filter_car_brand_id);
        }
        if ($request->has('filter_class_car_id')) {
            $carModels->where('class_car_id', $request->filter_class_car_id);
        }
        if ($request->has('filter_car_seat_from')) {
            $carModels->where('car_seat_from', ">=", $request->filter_car_seat_from);
        }
        if ($request->has('filter_car_seat_before')) {
            $carModels->where('car_seat_before', ">=", $request->filter_car_seat_before);
        }
        if ($request->has('filter_from_created_at')) {
            $carModels->where('created_at', '<=', Carbon::parse($request->filter_from_created_at)->format('Y-m-d H:i'));
        }

        if ($request->has('filter_to_created_at')) {
            $carModels->where('created_at', '<=', Carbon::parse($request->filter_to_created_at)->format('Y-m-d H:i'));
        }

        return CarModelResource::collection($carModels->orderByDesc('id')->limit($limit)->get());
    }

    public function store(CarModelRequest $request)
    {
        $marka = Marka::where('category_car_id', $request->category_car_id)->where('car_brand_id', $request->car_brand_id)->where('car_model', $request->car_model)->first();
        if ($marka) {
            return response()->json([
                'message' => 'Такой тип модели уже существует!'
            ], 422);
        }
        Marka::create([
            'name' => $request->name,
            'car_model' => $request->car_model,
            'car_seat_from' => $request->car_seat_from ?? null,
            'car_seat_before' => $request->car_seat_before ?? null,
            'car_brand_id' => $request->car_brand_id,
            'category_car_id' => $request->category_car_id,
            'class_car_id' => $request->class_car_id,
            'is_active' => $request->is_active ?? 1
        ]);
        return response()->json([
            'message' => 'Модель авто добавлен'
        ]);
    }

    public function edit($id)
    {
        $car_model = Marka::find($id);
        if ($car_model) {
            return new CarModelResource($car_model);
        }
        return response()->json(['message'=>'Not found']);
    }

    public function update(CarModelRequest $request, $id)
    {
        $car_model = Marka::find($id);
        if ($car_model) {
            $car_model->update([
                'name' => $request->name,
                'car_model' => $request->car_model,
                'car_seat_from' => $request->car_seat_from ?? null,
                'car_seat_before' => $request->car_seat_before ?? null,
                'car_brand_id' => $request->car_brand_id,
                'category_car_id' => $request->category_car_id,
                'class_car_id' => $request->class_car_id,
                'is_active' => $request->is_active ?? 1
            ]);
            return response()->json([
                'message' => 'Марка авто изменена'
            ]);
        } else {
            return response()->json([
                'message' => 'Модель авто не найден!'
            ], 422);
        }
    }
}
