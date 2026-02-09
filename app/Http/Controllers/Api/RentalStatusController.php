<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentalStatusController extends Controller
{
    public function index()
    {
        $rental_status= RentalStatus::orderBy('id')->get();
        return response()->json(['data'=>$rental_status]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:rental_statuses,code',
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $rental_status= RentalStatus::create($validator->validated());
        return response()->json(['data'=>$rental_status]);

    }

    public function show($id)
    {
        $rental_status= RentalStatus::find($id);
        if(!$rental_status){
            return response()->json(['message'=>'Not found']);
        }
        return response()->json(['data'=>$rental_status]);
    }

    public function update(Request $request, $id)
    {
        $rental_status = RentalStatus::find($id);
        if(!$rental_status){
            return response()->json(['message'=>'Not found']);
        }
        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|string|max:50|unique:rental_statuses,code,' . $rental_status->id,
            'name' => 'sometimes|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $rental_status->update($validator->validated());

        return response()->json(['data'=>$rental_status]);
    }

    public function destroy($id)
    {
        $rental_status = RentalStatus::find($id);
        if(!$rental_status){
            return response()->json(['message'=>'Not found']);
        }
        $rental_status->delete();

        return response()->json([
            'message' => 'Статус аренды удалён'
        ]);
    }
}
