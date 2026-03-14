<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentalTariffController extends Controller
{
    public function index()
    {
        $rental_tariff = RentalTariff::with('car.model_car.brand')->orderByDesc('id')->get();
        return response()->json(['data'=>$rental_tariff]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'performer_transport_id' => 'required|exists:mysql_performer.performer_transports,id',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'free_weekend_day' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $rental_tariff = RentalTariff::create($validator->validated());
        return response()->json(['data'=>$rental_tariff]);
    }

    public function show($id)
    {
        $rental_tariff= RentalTariff::with('car.model_car.brand')->find($id);
        if(!$rental_tariff){
            return response()->json(['message'=>'Not found']);
        }
        return response()->json(['data'=>$rental_tariff]);
    }

    public function update(Request $request, $id)
    {
        $rental_tariff = RentalTariff::find($id);
        if(!$rental_tariff){
            return response()->json(['message'=>'Not found']);
        }
        $validator = Validator::make($request->all(), [
            'duration_days' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'free_weekend_day' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $rental_tariff->update($validator->validated());

        return $rental_tariff;
    }
    
    public function destroy($id)
    {
        $rental_tariff= RentalTariff::find($id);
        if(!$rental_tariff){
            return response()->json(['message'=>'Not found']);
        }
        $rental_tariff->delete();

        return response()->json(['message' => 'Тариф удалён']);
    }
}
