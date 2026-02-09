<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarCategoryRequest;
use App\Http\Resources\CarCategoryResource;
use App\Models\CategoryCar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarCategoryController extends Controller
{
    public function index(Request $request)
    {
        $category_car = CategoryCar::query();
        $limit = 100;
        if($request->has('limit')){
            $limit = $request->limit;
        }
        if ($request->has('filter_id')) {
            $filter_id = $request->filter_id;
            if ($request->filter_id_codition == "equalOrMore") {
                $category_car->where('id', '>=', $filter_id);
            } elseif ($request->filter_id_condition == "equalOrLess") {
                $category_car->where('id', '>=', $filter_id);
            } elseif ($request->filter_id_condition == "less") {
                $category_car->where('id', '<', $filter_id);
            } elseif ($request->filter_id_condition == "more") {
                $category_car->where('id', '>', $filter_id);
            } else {
                $category_car->where('id', $filter_id);
            }
        }
        // filter by name
        if($request->has('filter_name_condition')){
            $name = $request->filter_name_condition;
            if($request->filter_name_condition != "startLike"){
                $category_car->where('name' , "Like" , "$name%");
            }
            elseif($request->filter_name_condition == "endLike"){
                $category_car->where('name' , "Like" , "%$name");
            }
            elseif($request->filter_name_condition == "include"){
                $category_car->where('name' , "Like" , "%$name%");
            }
        }
        if($request->has('filter_description_condition')){
            $name = $request->filter_description_condition;
            if($request->filter_name_condition == "startLike"){
                $category_car->where('description' , "Like" , "$name%");
            }
            elseif($request->filter_name_condition == "endLike"){
                $category_car->where('description' , "Like" , "%$name");
            }
            elseif($request->filter_name_condition == "include"){
                $category_car->where('description' , "Like" , "%$name%");
            }
        }
        if ($request->has('filter_from_created_at')) {
            $category_car->where('created_at', '>=', Carbon::parse($request->filter_from_created_at)->format('Y-m-d H:i'));
        }

        if ($request->has('filter_to_created_at')) {
            $category_car = $category_car->where('created_at', '<=', Carbon::parse($request->filter_to_created_at)->format('Y-m-d H:i'));
        }
        return CarCategoryResource::collection($category_car->orderByDesc('id')->limit($limit)->get());
    }

    public function store(CarCategoryRequest $request)
    {
        CategoryCar::updateOrCreate([
            'name' => $request->name,
            'description' => $request->description ?? null,
            'is_active' => $request->is_active ?? 1,
        ],[
            'name' => $request->name,
            'description' => $request->description ?? null,
            'is_active' => $request->is_active ?? 1,
        ]);
        return response()->json([
            'message' => 'Категория авто добавлен'
        ]);
    }

    public function show($id)
    {
        $category_car = CategoryCar::find($id);
        if ($category_car) {
            return new CarCategoryResource($category_car);
        }
        return response()->json($category_car);
    }

    public function update(CarCategoryRequest $request, $id)
    {
        $category_car = CategoryCar::find($id);
        if($category_car) {
            $category_car->update([
                'name' => $request->name,
                'description' => $request->description ?? null,
                'is_active' => $request->is_active ?? 1,
            ]);
            return response()->json([
                'message' => 'Категория авто изменена'
            ]);
        } else {
            return response()->json([
                'message' => 'Категория автомобиля не найден!'
            ], 422);
        }
    }
}
