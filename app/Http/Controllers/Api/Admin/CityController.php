<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index()
    {
        return City::orderBy('id')->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $city= City::create($validator->validated());
        return response()->json(['data'=>$city]);
    }

    public function show($id)
    {
        $city= City::find($id);
        if(!$city){
            return response()->json(['message'=>'Not found']);
        }
        return response()->json(['data'=>$city]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $city = City::find($id);
        
        if(!$city){
            return response()->json(['message'=>'Not found']);
        }
        $city->update($validator->validated());

        return response()->json(['data'=>$city]);
    }

    public function destroy($id)
    {
       $city= City::find($id);
       if(!$city){
        return response()->json(['message'=>'Not found']);
       }
       $city->delete();
        return response()->json([
            'message' => 'Город удалён'
        ]);
    }
}
