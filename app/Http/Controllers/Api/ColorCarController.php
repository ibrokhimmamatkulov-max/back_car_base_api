<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorCarRequest;
use App\Http\Resources\CarColorResource;
use App\Models\ColorCar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ColorCarController extends Controller
{
    public function index(Request $request)
    {
        $carColors = ColorCar::query()->with(['createdBy']);
        $limit = 100;
        if ($request->has('limit')) {
            $limit = $request->limit;
        }
        if ($request->has('filter_is_active')) {
            $carColors->where('is_active', $request->filter_is_active);
        }
        if ($request->has('filter_id')) {
            $filter_id = $request->filter_id;
            if ($request->filter_id_codition == "equalOrMore") {
                $carColors->where('id', '>=', $filter_id);
            } elseif ($request->filter_id_condition == "equalOrLess") {
                $carColors->where('id', '>=', $filter_id);
            } elseif ($request->filter_id_condition == "less") {
                $carColors->where('id', '<', $filter_id);
            } elseif ($request->filter_id_condition == "more") {
                $carColors->where('id', '>', $filter_id);
            } else {
                $carColors->where('id', $filter_id);
            }
        }
        // filter by name
        if ($request->has('filter_name')) {
            $name = $request->filter_name;
            if ($request->filter_name_condition == "startLike") {
                $carColors->where('name', "Like", "$name%");
            } elseif ($request->filter_name_condition == "endLike") {
                $carColors->where('name', "Like", "%$name");
            } elseif ($request->filter_name_condition == "include") {
                $carColors->where('name', "Like", "%$name%");
            } else {
                $carColors->where('name', $name);
            }
        }
        if ($request->has('filter_name_tj')) {
            $name_tj = $request->filter_name_tj;
            if ($request->filter_name_tj_condition == "startLike") {
                $carColors->where('name_tj', "Like", "$name_tj%");
            } elseif ($request->filter_name_tj_condition == "endLike") {
                $carColors->where('name_tj', "Like", "%$name_tj");
            } elseif ($request->filter_name_tj_condition == "include") {
                $carColors->where('name_tj', "Like", "%$name_tj%");
            } else {
                $carColors->where('name_tj', $name_tj);
            }
        }
        if ($request->has('filter_name_for_sms')) {
            $name_for_sms = $request->filter_name_for_sms;
            if ($request->filter_name_for_sms_condition == "startLike") {
                $carColors->where('name_for_sms', "Like", "$name_for_sms%");
            } elseif ($request->filter_name_for_sms_condition == "endLike") {
                $carColors->where('name_for_sms', "Like", "%$name_for_sms");
            } elseif ($request->filter_name_for_sms_condition == "include") {
                $carColors->where('name_for_sms', "Like", "%$name_for_sms%");
            } else {
                $carColors->where('name_for_sms', $name_for_sms);
            }
        }
        if ($request->has('filter_from_created_at')) {
            $carColors->where('created_at', '>=', Carbon::parse($request->filter_from_created_at)->format('Y-m-d H:i'));
        }

        if ($request->has('filter_to_created_at')) {
            $carColors->where('created_at', '<=', Carbon::parse($request->filter_to_created_at)->format('Y-m-d H:i'));
        }
        return CarColorResource::collection($carColors->limit($limit)->get());
    }

    public function store(ColorCarRequest $request)
    {
        try {
            $validated = $request->validated();
            $color_car = new ColorCar();
            $color_car->name = $validated['name'];
            $color_car->name_tj = $validated['name_tj'];
            $color_car->name_for_sms = $validated['name_for_sms'];
            $color_car->is_active = $validated['is_active'] ?? 1;
            $color_car->created_by = auth()->id();
            $color_car->save();
            return response()->json([
                'message' => 'Цвет добавлен'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Произошла системная ошибка!',
                'errors' => $exception->getMessage()
            ], 422);
        }
    }

    public function edit($id)
    {
        try {
            $color_car = ColorCar::find($id);
            if ($color_car) {
                return new CarColorResource($color_car);
            }
            return response()->json($color_car);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Произошла системная ошибка!',
                'errors' => $exception->getMessage()
            ], 422);
        }
    }

    public function update(ColorCarRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $color_car = ColorCar::find($id);
            if ($color_car) {
                $color_car->name = $validated['name'];
                $color_car->name_tj = $validated['name_tj'];
                $color_car->name_for_sms = $validated['name_for_sms'];
                $color_car->is_active = $validated['is_active'] ?? 1;
                $color_car->update();
                return response()->json([
                    'message' => 'Цвет изменён'
                ]);
            } else {
                return response()->json([
                    'message' => 'Not found'
                ], 404);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Произошла системная ошибка!',
                'errors' => $exception->getMessage()
            ], 422);
        }
    }
}
