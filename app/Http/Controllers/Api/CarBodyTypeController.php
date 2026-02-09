<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BodyTypeRequest;
use App\Http\Resources\BodyTypeResource;
use App\Models\BodyType;
use App\Models\PerformerCarOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarBodyTypeController extends Controller
{
    public function index(Request $request)
    {
        $body_types = BodyType::query();
        $limit = 100;
        if ($request->has('limit')) {
            $limit = $request->limit;
        }
        if ($request->has('filter_is_active')) {
            $body_types->where('is_active', $request->filter_is_active);
        }
        // filter by id
        if ($request->has('filter_id')) {
            $filter_id = $request->filter_id;
            if ($request->filter_id_codition == "equalOrMore") {
                $body_types->where('id', '>=', $filter_id);
            } elseif ($request->filter_id_condition == "equalOrLess") {
                $body_types->where('id', '>=', $filter_id);
            } elseif ($request->filter_id_condition == "less") {
                $body_types->where('id', '<', $filter_id);
            } elseif ($request->filter_id_condition == "more") {
                $body_types->where('id', '>', $filter_id);
            } else {
                $body_types->where('id', $filter_id);
            }
        }
        // filter by name
        if ($request->has('filter_name')) {
            $name = $request->filter_name;
            if ($request->filter_name_condition == "startLike") {
                $body_types->where('name', "Like", "$name%");
            } elseif ($request->filter_name_condition == "endLike") {
                $body_types->where('name', "Like", "%$name");
            } elseif ($request->filter_name_condition == "include") {
                $body_types->where('name', "Like", "%$name%");
            } else {
                $body_types->where('name', $name);
            }
        }
        if ($request->has('filter_category_car_id')) {
            $body_types->where('category_car_id', $request->filter_category_car_id);
        }
        if ($request->has('filter_from_created_at')) {
            $body_types->where('created_at', '>=', Carbon::parse($request->filter_from_created_at)->format('Y-m-d H:i'));
        }

        if ($request->has('filter_to_created_at')) {
            $body_types->where('created_at', '<=', Carbon::parse($request->filter_to_created_at)->format('Y-m-d H:i'));
        }
        return BodyTypeResource ::collection($body_types->limit($limit)->get());
    }

    public function store(BodyTypeRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $body_type = BodyType::create($validated);
            DB::commit();
            return $this->jsonResponse(['message' => 'Создан тип кузова']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Произошла системная ошибка!',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $body_type = BodyType::find($id);
        if ($body_type) {
            $body_type = new BodyTypeResource($body_type);
        }
        return $this->jsonResponse($body_type);
    }

    public function update(BodyTypeRequest $request, $id)
    {
        $type_body = BodyType::find($id);
        if ($type_body) {
            $validated = $request->validated();
            $type_body->update($validated);
            return response()->json([
                'message' => 'Тип кузова изменен'
            ]);
        } else {
            return $this->jsonResponse(['message' => 'Not found!'], 404);
        }
    }
}
