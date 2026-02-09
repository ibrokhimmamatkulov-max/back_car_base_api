<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarOptionRequest;
use App\Http\Resources\CarOptionResource;
use App\Models\CarOption;
use App\Services\CarOptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarOptionController extends Controller
{
     public function index(Request $request, CarOptionService $carOptionService)
    {
        return $carOptionService->getCarOptions($request);
    }

    public function store(CarOptionRequest $request)
    {
        DB::beginTransaction();
        try {
            CarOption::updateOrCreate([
                'name' => $request->name,
                'category_car_id' => $request->category_car_id,
                'is_active' => $request->is_active ?? 1
            ], [
                'name' => $request->name,
                'category_car_id' => $request->category_car_id,
                'is_active' => $request->is_active ?? 1
            ]);

            DB::commit();
            return response()->json([
                'message' => 'Доп опция авто добавлен'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => 'Произошла системная ошибка!',
                'errors' => $exception->getMessage()
            ], 422);
        }
    }

    public function edit($id)
    {
        try {
            $car_option = CarOption::find($id);
            if ($car_option) {
                return new CarOptionResource($car_option);
            }
            return response()->json($car_option);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Произошла системная ошибка!',
                'errors' => $exception->getMessage()
            ], 422);
        }
    }

    public function update(CarOptionRequest $request, $id)
    {
        try {
            $car_option = CarOption::find($id);
            if ($car_option) {
                $car_option->update([
                    'category_car_id' => $request->category_car_id,
                    'name' => $request->name,
                    'is_active' => $request->is_active ?? 1
                ]);

                return response()->json([
                    'message' => 'Доп опция авто добавлен'
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
