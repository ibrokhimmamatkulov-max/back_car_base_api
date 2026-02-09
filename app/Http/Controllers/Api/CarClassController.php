<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarClassRequest;
use App\Http\Resources\CarClassResource;
use App\Models\Car\ClassCar;
use Illuminate\Http\Request;

class CarClassController extends Controller
{
    public function index()
    {
        return CarClassResource::collection(ClassCar::query()->get());
    }

    public function store(CarClassRequest $request)
    {
        ClassCar::updateOrCreate([
            'name' => $request->name,
            'category_car_id' => $request->category_car_id,
            'is_active' => $request->is_active ?? 1,
        ],[
            'name' => $request->name,
            'category_car_id' => $request->category_car_id,
            'is_active' => $request->is_active ?? 1,
        ]);
        return response()->json([
            'message' => 'Класс авто добавлен'
        ]);
    }

    public function edit($id)
    {
        $class_car = ClassCar::find($id);
        if ($class_car) {
            return new CarClassResource($class_car);
        }
        return response()->json($class_car);
    }

    public function update(CarClassRequest $request, $id)
    {
        $car_class = ClassCar::find($id);
        if($car_class) {
            $car_class->update([
                'name' => $request->name,
                'category_car_id' => $request->category_car_id,
                'is_active' => $request->is_active ?? 1,
            ]);
            return response()->json([
                'message' => 'Класс авто изменена'
            ]);
        } else {
            return response()->json([
                'message' => 'Класс авто не найден!'
            ], 422);
        }
    }
}
