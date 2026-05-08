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
        return $this->success(RentalStatus::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:auto_baza.rental_statuses,code',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $status = RentalStatus::create($validator->validated());
        return $this->success($status, 'Статус аренды создан', 201);
    }

    public function show(int $id)
    {
        $status = RentalStatus::find($id);
        if (!$status) {
            return $this->error('Статус аренды не найден', 404);
        }
        return $this->success($status);
    }

    public function update(Request $request, int $id)
    {
        $status = RentalStatus::find($id);
        if (!$status) {
            return $this->error('Статус аренды не найден', 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|string|max:50|unique:auto_baza.rental_statuses,code,' . $status->id,
            'name' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $status->update($validator->validated());
        return $this->success($status);
    }

    public function destroy(int $id)
    {
        $status = RentalStatus::find($id);
        if (!$status) {
            return $this->error('Статус аренды не найден', 404);
        }
        $status->delete();
        return $this->success(null, 'Статус аренды удалён');
    }
}
