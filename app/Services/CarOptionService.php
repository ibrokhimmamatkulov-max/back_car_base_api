<?php

namespace App\Services;

use App\Http\Resources\Cars\CarOptionResource;
use App\Models\CarOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class CarOptionService
{
    public function getCarOptions(Request $request)
    {
        $limit = 100;
        if (isset($request->limit) && ctype_digit($request->limit) && $request->limit > 0) {
            $limit = $request->limit;
        }

        $car_option = CarOption::query()->where('is_active', 1);
        
        // Filter id
        if ($request->has('filter_id') || in_array($request->filter_id_condition, ["nullable", "notNullable"])) {
            $car_option = $car_option->FilterInt(
                "id",
                $request->filter_id_condition ?? null,
                $request->filter_id
            );
        }
        
        // Filter category_car_id
        if ($request->has('filter_category_car_id') || in_array($request->filter_category_car_id_condition, ["nullable", "notNullable"])) {
            $car_option = $car_option->FilterInt(
                "category_car_id",
                $request->filter_category_car_id_condition ?? null,
                $request->filter_category_car_id
            );
        }
        
        // Filter category_car_name (relation)
        if ($request->has('filter_category_car_name') || in_array($request->filter_category_car_name_condition, ["nullable", "notNullable"])) {
            $car_option = $car_option->FilterRelationStringHas(
                "category_car",
                "name",
                $request->filter_category_car_name_condition ?? null,
                $request->filter_category_car_name
            );
        }
        
        // Filter name
        if ($request->has('filter_name') || in_array($request->filter_name_condition, ["nullable", "notNullable"])) {
            $car_option = $car_option->FilterString(
                "name",
                $request->filter_name_condition ?? null,
                $request->filter_name
            );
        }

        // // Filter allowance_id
        // if ($request->has('filter_allowance_id') || in_array($request->filter_allowance_id_condition, ["nullable", "notNullable"])) {
            //     $car_option = $car_option->FilterInt(
        //         "allowance_id",
        //         $request->filter_allowance_id_condition ?? null,
        //         $request->filter_allowance_id
        //     );
        // }
        
        // // Filter allowance_name (separate DB query)
        // if ($request->has('filter_allowance_name') || in_array($request->filter_allowance_name_condition, ["nullable", "notNullable"])) {
            //     $car_option = $car_option->FilterString(
                //         "allowance_name",
                //         $request->filter_allowance_name_condition ?? null,
        //         null
        //     );

        //     $allowanceIds = DB::connection('mysql_taxi')
        //         ->table('allowances')
        //         ->where('name', 'like', '%' . $request->filter_allowance_name . '%')
        //         ->pluck('id');
        
        //     $car_option = $car_option->whereIn('allowance_id', $allowanceIds);
        // }
        
        // Filter is_active
        if ($request->has('filter_is_active') || in_array($request->filter_is_active_condition, ["nullable", "notNullable"])) {
            $car_option = $car_option->FilterInt(
                "is_active",
                $request->filter_is_active_condition ?? null,
                $request->filter_is_active
            );
        }
        
        // Date filters
        if ($request->has('filter_from_created_at')) {
            $car_option->where('created_at', '>=', Carbon::parse($request->filter_from_created_at)->format('Y-m-d H:i'));
        }

        if ($request->has('filter_to_created_at')) {
            $car_option->where('created_at', '<=', Carbon::parse($request->filter_to_created_at)->format('Y-m-d H:i'));
        }
        
        $car_option = $car_option->orderByDesc('id')->limit($limit)->get();
        return CarOptionResource::collection($car_option);
    }
}
