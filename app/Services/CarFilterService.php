<?php

namespace App\Services;

use App\Models\Performer;
use App\Models\PerformerTariff;
use App\Models\PerformerTransport;
use Illuminate\Http\Request;

class CarFilterService
{
    public static function applyFilters($query,Request $request)
    {
        if ($request->has('filter_id')) {
            $filter_id = $request->filter_id;
            $condition = null;
            if ($request->has('filter_id_condition')) {
                $condition = $request->filter_id_condition;
            }
            $query = $query->FilterInt('performer_transports.id', $condition, $filter_id);
        }
        if ($request->has('filter_division_id')) {
            $filter_division_id = $request->filter_division_id;
            $condition = null;
            if ($request->has('filter_division_id_condition')) {
                $condition = $request->filter_division_id_condition;
            }
            $query = $query->FilterInt('performer_transports.division_id', $condition, $filter_division_id);
        }
        if ($request->has('filter_color_id')) {
            $filter_color_id = $request->filter_color_id;
            $condition = null;
            if ($request->has('filter_color_id_condition')) {
                $condition = $request->filter_color_id_condition;
            }
            $query = $query->FilterInt('performer_transports.color_id', $condition, $filter_color_id);
        }
        if ($request->has('filter_year_of_issue')) {
            $filter_year_of_issue = $request->filter_year_of_issue;
            $condition = null;
            if ($request->has('filter_year_of_issue_condition')) {
                $condition = $request->filter_year_of_issue_condition;
            }
            $query = $query->FilterInt('performer_transports.year_of_issue', $condition, $filter_year_of_issue);
        }
        if ($request->has('filter_body_type_id')) {
            $filter_body_type_id = $request->filter_body_type_id;
            $condition = null;
            if ($request->has('filter_body_type_id_condition')) {
                $condition = $request->filter_body_type_id_condition;
            }
            $query = $query->FilterInt('performer_transports.body_type_id', $condition, $filter_body_type_id);
        }
        if ($request->has('filter_status')) {
            $filter_status = $request->filter_status;
            $condition = null;
            if ($request->has('filter_status_condition')) {
                $condition = $request->filter_status_condition;
            }
            $query = $query->FilterInt('performer_transports.active', $condition, $filter_status);
        }
        if ($request->has('filter_car_class_id')) {
            $filter_car_class_id = $request->filter_car_class_id;
            $relation = "model_car";
            $column = "class_car_id";
            $condition = null;
            if ($request->has('filter_car_class_id_condition')) {
                $condition = $request->filter_car_class_id_condition;
            }
            $query = $query->FilterRelationIntHas($relation, $column, $condition, $filter_car_class_id);
        }
        if ($request->has('filter_car_brand_id')) {
            $filter_car_brand_id = $request->filter_car_brand_id;
            $relation = "model_car";
            $column = "car_brand_id";
            $condition = null;
            if ($request->has('filter_car_brand_id_condition')) {
                $condition = $request->filter_car_brand_id_condition;
            }
            $query = $query->FilterRelationIntHas($relation, $column, $condition, $filter_car_brand_id);
        }
        if ($request->has('filter_car_model_id')) {
            $filter_car_model_id = $request->filter_car_model_id;
            $column = "car_model_id";
            $condition = null;
            if ($request->has('filter_car_model_id_condition')) {
                $condition = $request->filter_car_model_id_condition;
            }
            $query = $query->FilterInt($column, $condition, $filter_car_model_id);
        }

        // Фильтр по тип топлива
      
        if ($request->has('filter_fuel_type_name') || ($request->filter_fuel_type_name_condition == "nullable" || $request->filter_fuel_type_name_condition == "notNullable")) {
            $relation = "fuel_type";
            $column = "name";
            $value = $request->filter_fuel_type_name;
            $condition = null;
            if ($request->has('filter_fuel_type_name_condition')) {
                $condition = $request->filter_fuel_type_name_condition;
            }
            $query = $query->FilterRelationStringHas($relation, $column, $condition, $value);
        }
        
        
        if ($request->has('filter_car_option_id')) {
            $filter_car_option_id = $request->filter_car_option_id;
            $relation = "car_options";
            $column = "car_option_id";
            $condition = null;
            if ($request->has('filter_car_option_id_condition')) {
                $condition = $request->filter_car_option_id_condition;
            }
            $query = $query->FilterRelationIntHas($relation, $column, $condition, $filter_car_option_id);
        }
        if ($request->has('filter_carrying_capacity')) {
            $filter_carrying_capacity = $request->filter_carrying_capacity;
            $condition = null;
            if ($request->has('filter_carrying_capacity_condition')) {
                $condition = $request->filter_carrying_capacity_condition;
            }
            $query = $query->FilterInt('performer_transports.cargo_properties->carrying_capacity', $condition, $filter_carrying_capacity);
        }
        if ($request->has('filter_car_length')) {
            $filter_car_length = $request->filter_car_length;
            $condition = null;
            if ($request->has('filter_car_length_condition')) {
                $condition = $request->filter_car_length_condition;
            }
            $query = $query->FilterInt('performer_transports.cargo_properties->length', $condition, $filter_car_length);
        }
        if ($request->has('filter_car_width')) {
            $filter_car_width = $request->filter_car_width;
            $condition = null;
            if ($request->has('filter_car_width_condition')) {
                $condition = $request->filter_car_width_condition;
            }
            $query = $query->FilterInt('performer_transports.cargo_properties->width', $condition, $filter_car_width);
        }
        if ($request->has('filter_car_height')) {
            $filter_car_height = $request->filter_car_height;
            $condition = null;
            if ($request->has('filter_car_height_condition')) {
                $condition = $request->filter_car_height_condition;
            }
            $query = $query->FilterInt('performer_transports.cargo_properties->height', $condition, $filter_car_height);
        }
        if ($request->has('filter_from_created_at')) {
            $column = "performer_transports.created_at";
            $from_created_at = $request->filter_from_created_at;
            $condition = null;
            if ($request->has('filter_from_created_at_condition')) {
                $condition = $request->filter_from_created_at_condition;
            }
            $query = $query->FilterString($column, $condition, $from_created_at);
        }
        if ($request->has('filter_car_park_id')) {
            $column = 'car_park_id';
            $full_name = $request->filter_car_park_id;
            $condition = null;
            if ($request->has('filter_car_park_id_condition')) {
                $condition = $request->filter_car_park_id_condition;
            }
            $query = $query->FilterString($column, $condition, $full_name);
        }

        if ($request->has('filter_car_number')) {
            $column = "performer_transports.car_number";
            $car_number = $request->filter_car_number;
            $condition = null;
            if ($request->has('filter_car_number_condition')) {
                $condition = $request->filter_car_number_condition;
            }
            $query = $query->FilterString($column, $condition, $car_number);
        }

        if ($request->has('filter_to_created_at')) {
            $column = "performer_transports.created_at";
            $to_created_at = $request->filter_to_created_at;
            $condition = null;
            if ($request->has('filter_to_created_at_condition')) {
                $condition = $request->filter_to_created_at_condition;
            }
            $query = $query->FilterString($column, $condition, $to_created_at);
        }
        if ($request->has('filter_count_seat')) {
            $filter_count_seat = $request->filter_count_seat;
            $condition = null;
            if ($request->has('filter_count_seat_condition')) {
                $condition = $request->filter_count_seat_condition;
            }
            $query = $query->FilterInt('performer_transports.count_seat', $condition, $filter_count_seat);
        }

        if (isset($request->filter_fio)) {
            $fio = preg_replace('/^ +| +$|( ) +/m', '$1', $request->filter_fio);
            $arr = explode(" ", $fio);
            $perfomers = new Performer();
            foreach ($arr as $key => $value) {
                if ($key == 0) {
                    $perfomers = $perfomers->where('last_name', $value);
                } elseif ($key == 1) {
                    $perfomers = $perfomers->where('first_name', $value);
                } else {
                    $perfomers = $perfomers->where('patronymic', $value);
                }
            }

            $perfomers = $perfomers->pluck('id');
            $query = PerformerTransport::whereIn('performer_id', $perfomers);
        }

        // if (isset($request->filter_login)) {
        //     $perfomers = Performer::where('login', trim($request->filter_login))->pluck('id');
        //     $query = PerformerTransport::whereIn('performer_id', $perfomers);
        // }

        if (isset($request->filter_category_car_id)) {
            if (!is_numeric($request->filter_category_car_id))
                return response()->json([
                    'success' => false,
                    'message' => "filter_category_car_id принимает только цифру",
                ]);
            $query = PerformerTransport::where('car_model_id', $request->filter_category_car_id);
        }


        // if (isset($request->filter_tarif_id)) {
        //     if (!is_numeric($request->filter_tarif_id))
        //         return response()->json([
        //             'success' => false,
        //             'message' => "filter_category_car_id принимает только цифру",
        //         ]);
        //     $tariffs = PerformerTariff::where('tariff_id', $request->filter_tarif_id)->get();
        //     $performerIds = $tariffs->pluck('performer_id')->toArray();
        //     $query = PerformerTransport::where('active', 1)
        //         ->where('performer_id', $performerIds)
        //         ->whereHas('car_drivers', function ($query) {
        //             $query->whereNotNull('driver_id');
        //             $query->where('is_attached', 1);
        //         });
        // }


        if (isset($request->filter_condition_id)) {
            if (!is_numeric($request->filter_condition_id))
                return response()->json([
                    'success' => false,
                    'message' => "filter_category_car_id принимает только цифру",
                ]);
            $query = PerformerTransport::where('condition_id', $request->filter_condition_id);
        }

        if ($request->has('filter_journal_car')) {
            $filter_journal_car = $request->filter_journal_car;
            switch ($filter_journal_car) {
                case "ALL_LIST":
                    break;
                case "ACTIVE":
                    $query = $query->whereHas('car_connection', function ($query) use ($filter_journal_car) {
                        $query->where('id', '=', 1);
                    });
                    break;
                case "DISMISSED":
                    $query = $query->whereHas('car_connection', function ($query) use ($filter_journal_car) {
                        $query->where('id', '=', 3);
                    });
                    break;
                case "CREATED_BY_ME":
                    $query = $query->where('created_user_id', auth()->id());

                    break;
                case "INSPECTION_IN_THE_OFFICE":
                    break;
                case "DOUBLES":
                    break;
                default;

                    break;
            }
        }
        return $query;
    }
}