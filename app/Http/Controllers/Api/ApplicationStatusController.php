<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApplicationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationStatusController extends Controller
{
    public function index()
    {
        return $this->success(ApplicationStatus::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:auto_baza.application_statuses,code',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $status = ApplicationStatus::create($validator->validated());
        return $this->success($status, 'Статус заявки создан', 201);
    }

    public function show(int $id)
    {
        $status = ApplicationStatus::find($id);
        if (!$status) {
            return $this->error('Статус заявки не найден', 404);
        }
        return $this->success($status);
    }

    public function update(Request $request, int $id)
    {
        $status = ApplicationStatus::find($id);
        if (!$status) {
            return $this->error('Статус заявки не найден', 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:auto_baza.application_statuses,code,' . $status->id,
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $status->update($validator->validated());
        return $this->success($status);
    }

    public function destroy(int $id)
    {
        $status = ApplicationStatus::find($id);
        if (!$status) {
            return $this->error('Статус заявки не найден', 404);
        }
        $status->delete();
        return $this->success(null, 'Статус заявки удалён');
    }
}
