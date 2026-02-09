<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentalApplicationController extends Controller
{
    public function index()
    {
        $rental_application= RentalApplication::with([
            // 'car.model_car.brand',
            'car',
            'tariff',
            'user',
            'city',
            'status'
        ])->orderByDesc('id')->get();
        return response()->json(['data'=>$rental_application]);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'performer_transport_id' => 'required|exists:performer_transports,id',
            'rental_tariff_id' => 'required|exists:rental_tariffs,id',
            'user_id' => 'nullable|exists:users,id',
            'phone' => 'required|string|max:30',
            'city_id' => 'required|exists:cities,id',
            'promo_code' => 'nullable|string|max:50',
            'status_id' => 'required|exists:application_statuses,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $rental_application= RentalApplication::create($validator->validated());
        return response()->json(['data'=>$rental_application]);
    }

    public function show($id)
    {
        $rental_application= RentalApplication::with([
            'car.model_car.brand',
            'tariff',
            'user',
            'city',
            'status'
        ])->find($id);
        if(!$rental_application){
            return response()->json(['message'=>'Not found']);
        }
        return response()->json(['data'=>$rental_application]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_id' => 'required|exists:application_statuses,id',
            'promo_code' => 'nullable|string|max:50',
        ]);
        $rental_application = RentalApplication::find($id);
        if(!$rental_application){
            return response()->json(['message'=>'Not found']);
        }
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $rental_application->update($validator->validated());
        return response()->json(['data'=>$rental_application]);
    }

    public function destroy($id)
    {
       $rental_application= RentalApplication::find($id);
        if(!$rental_application){
            return response()->json(['message'=>'Not found']);
        }
        $rental_application->delete();
        return response()->json([
            'message' => 'Заявка удалена'
        ]);
    }
}
