<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarClassRequest;
use App\Http\Resources\CarClassResource;
use App\Models\Car\ClassCar;

class CarClassController extends Controller
{
    public function index()
    {
        return $this->success(CarClassResource::collection(ClassCar::query()->get()));
    }

    public function store(CarClassRequest $request)
    {
        ClassCar::updateOrCreate(
            ['name' => $request->name, 'category_car_id' => $request->category_car_id],
            ['is_active' => $request->is_active ?? 1]
        );
        return $this->success(null, 'Класс авто добавлен', 201);
    }

    public function edit(int $id)
    {
        $class_car = ClassCar::find($id);
        if (!$class_car) {
            return $this->error('Класс авто не найден', 404);
        }
        return $this->success(new CarClassResource($class_car));
    }

    public function update(CarClassRequest $request, int $id)
    {
        $car_class = ClassCar::find($id);
        if (!$car_class) {
            return $this->error('Класс авто не найден', 404);
        }
        $car_class->update([
            'name'           => $request->name,
            'category_car_id'=> $request->category_car_id,
            'is_active'      => $request->is_active ?? 1,
        ]);
        return $this->success(null, 'Класс авто изменён');
    }
}
