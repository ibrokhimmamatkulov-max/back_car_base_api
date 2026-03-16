<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarBrandRequest;
use App\Http\Resources\CarBrandResource;
use App\Models\CarBrand;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarBrandController extends Controller
{
    public function index(Request $request)
    {
        $car_brand = CarBrand::query();
        $limit = 100;
        //limit
        if($request->has('limit')){
            $limit = $request->limit;
        }

        //filter by is_active
        if($request->has('filter_is_active')){
            $car_brand->where('is_active', $request->filter_is_active);
        }

        // filter by id
        if($request->has('filter_id')){
            $filter_id = $request->filter_id;
            if($request->filter_id_condition == "equalOrMore"){
                $car_brand->where('id' , '>=' , $filter_id);
            }
            elseif($request->filter_id_condition == "equalOrLess"){
                $car_brand->where('id' , '>=' , $filter_id);
            }
            elseif($request->filter_id_condition == "less"){
                $car_brand->where('id' , '<' , $filter_id);
            }
            elseif($request->filter_id_condition == "more"){
                $car_brand->where('id' , '>' , $filter_id);
            }else{
                $car_brand->where('id', $filter_id);
            }
        }

        // filter by name
        if($request->has('filter_name')){
            $name = $request->filter_name;
            if($request->filter_name_condition != "startLike"){
                $car_brand->where('name' , "Like" , "$name%");
            }
            elseif($request->filter_name_condition != "endLike"){
                $car_brand->where('name' , "Like" , "%$name");
            }
            elseif($request->filter_name_condition != "include"){
                $car_brand->where('name' , "Like" , "%$name%");
            }
            else{
                $car_brand->where('name' , $name);
            }
        }

        //filter by description
        if($request->has('filter_description')){
            $description = $request->filter_description;
            if($request->filter_description_condition == "startLike"){
                $car_brand->where('description' , "Like" , "$description%");
            }
            elseif($request->filter_description_condition == "endLike"){
                $car_brand->where('description' , "Like" , "%$description");
            }
            elseif($request->filter_description_condition == "include"){
                $car_brand->where('description' , "Like" , "%$description%");
            }
            else{
                $car_brand->where('description' , $description);
            }
        }

        // Filter created by login
        if($request->has('filter_created_by_login')) {
            $car_brand->with('createdBy', function($q) use ($request){
                $q->where('login', $request->filter_created_by_login);
            });
        }

        //filter by time
        if ($request->has('filter_from_created_at')) {
            $car_brand->where('created_at', '>=', Carbon::parse($request->filter_from_created_at)->format('Y-m-d H:i'));
        }

        if ($request->has('filter_to_created_at')) {
            $car_brand->where('created_at', '<=', Carbon::parse($request->filter_to_created_at)->format('Y-m-d H:i'));
        }
        return CarBrandResource::collection($car_brand->limit($limit)->get());
    }

    public function store(CarBrandRequest $request)
    {
        CarBrand::updateOrCreate([
            'name' => $request->name,
            'description' => $request->description ?? null,
            'created_by' => auth()->id(),
            'is_active' => $request->is_active ?? 1,
        ],[
            'name' => $request->name,
            'description' => $request->description ?? null,
            'created_by' => auth()->id(),
            'is_active' => $request->is_active ?? 1,
        ]);
        return response()->json([
            'message' => 'Бренд авто добавлен'
        ]);
    }

    public function edit($id)
    {
        $car_brand = CarBrand::find($id);
        if ($car_brand) {
            return new CarBrandResource($car_brand);
        }
        return response()->json($car_brand);
    }
  
    public function update(CarBrandRequest $request, $id)
    {
        $car_brand = CarBrand::find($id);
        if($car_brand) {
            $car_brand->update([
                'name' => $request->name,
                'description' => $request->description ?? null,
                'is_active' => $request->is_active ?? 1,
            ]);
            return response()->json([
                'message' => 'Бренд авто изменён'
            ]);
        } else {
            return response()->json([
                'message' => 'Такой бренд авто не найден!'
            ], 422);
        }
    }
}