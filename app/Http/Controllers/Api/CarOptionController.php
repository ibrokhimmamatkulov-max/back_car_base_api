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
            CarOption::updateOrCreate(
                ['name' => $request->name, 'category_car_id' => $request->category_car_id],
                ['is_active' => $request->is_active ?? 1]
            );
            DB::commit();
            return $this->success(null, 'Доп. опция авто добавлена', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Произошла системная ошибка: ' . $e->getMessage(), 500);
        }
    }

    public function edit(int $id)
    {
        $car_option = CarOption::find($id);
        if (!$car_option) {
            return $this->error('Опция не найдена', 404);
        }
        return $this->success(new CarOptionResource($car_option));
    }

    public function update(CarOptionRequest $request, int $id)
    {
        $car_option = CarOption::find($id);
        if (!$car_option) {
            return $this->error('Опция не найдена', 404);
        }
        $car_option->update([
            'category_car_id' => $request->category_car_id,
            'name'            => $request->name,
            'is_active'       => $request->is_active ?? 1,
        ]);
        return $this->success(null, 'Доп. опция авто изменена');
    }
}
