<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gearbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GearboxController extends Controller
{
    public function index()
    {
        return $this->success(Gearbox::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:gearboxes,code',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $gearbox = Gearbox::create($validator->validated());
        return $this->success($gearbox, 'Коробка передач создана', 201);
    }

    public function show(int $id)
    {
        $gearbox = Gearbox::find($id);
        if (!$gearbox) {
            return $this->error('Коробка передач не найдена', 404);
        }
        return $this->success($gearbox);
    }

    public function update(Request $request, int $id)
    {
        $gearbox = Gearbox::find($id);
        if (!$gearbox) {
            return $this->error('Коробка передач не найдена', 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|string|max:50|unique:gearboxes,code,' . $gearbox->id,
            'name' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $gearbox->update($validator->validated());
        return $this->success($gearbox);
    }

    public function destroy(int $id)
    {
        $gearbox = Gearbox::find($id);
        if (!$gearbox) {
            return $this->error('Коробка передач не найдена', 404);
        }
        $gearbox->delete();
        return $this->success(null, 'Коробка передач удалена');
    }
}
