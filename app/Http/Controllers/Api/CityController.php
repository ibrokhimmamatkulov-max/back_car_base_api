<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index()
    {
        return $this->success(City::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $city = City::create($validator->validated());
        return $this->success($city, 'Город создан', 201);
    }

    public function show(int $id)
    {
        $city = City::find($id);
        if (!$city) {
            return $this->error('Город не найден', 404);
        }
        return $this->success($city);
    }

    public function update(Request $request, int $id)
    {
        $city = City::find($id);
        if (!$city) {
            return $this->error('Город не найден', 404);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $city->update($validator->validated());
        return $this->success($city);
    }

    public function destroy(int $id)
    {
        $city = City::find($id);
        if (!$city) {
            return $this->error('Город не найден', 404);
        }
        $city->delete();
        return $this->success(null, 'Город удалён');
    }
}
