<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Resources\CarConditionResource;
use App\Models\CarCondition;
use Illuminate\Http\Request;

class CarConditionController extends Controller
{
    public function index(Request $request)
    {
        $limit = 100;
        if ($request->has("limit")) {
            if ((int)$request->limit > 0) {
                $limit = $request->limit;
            }
        }
        $сarСondition = CarCondition::query();

        if ($request->has('filter_id')) {
            $filter_id = $request->filter_id;
            $condition = null;
            if ($request->has('filter_id_condition')) {
                $condition = $request->filter_id_condition;
            }
            $сarСondition = $сarСondition->FilterInt('car_conditions.id', $condition, $filter_id);
        }

        if ($request->has('filter_name')) {
            $filter_name = $request->filter_name;
            $condition = null;
            if ($request->has('filter_name_condition')) {
                $condition = $request->filter_name_condition;
            }
            $сarСondition = $сarСondition->FilterString('car_conditions.name', $condition, $filter_name);
        }

        if ($request->has('filter_level')) {
            $filter_level = $request->filter_level;
            $condition = null;
            if ($request->has('filter_level_condition')) {
                $condition = $request->filter_level_condition;
            }
            $сarСondition = $сarСondition->FilterInt('car_conditions.level', $condition, $filter_level);
        }

        $сarСondition = $сarСondition->orderByDesc("id")->limit($limit)->get();
        return response()->json(CarConditionResource::collection($сarСondition));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $car = CarCondition::create($data);
        return CarConditionResource::make($car);
    }
  
    public function edit($id)
    {
        $car = CarCondition::find($id);
        if ($car) {
            $car = new CarConditionResource($car);
        }
        return $this->jsonResponse($car);
    }

    public function update(UpdateRequest $request, CarCondition $carCondition)
    {
        $data = $request->validated();
        $carCondition->update($data);
        return CarConditionResource::make($carCondition);
    } 
}
