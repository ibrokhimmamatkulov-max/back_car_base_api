<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
    public function index()
    {
        $rental = Rental::with(['car.model_car.brand', 'client', 'manager', 'status'])
            ->orderByDesc('id')->get();
        return $this->success($rental);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'performer_transport_id' => 'required|exists:auto_baza.performer_transports,id',
            'performer_id'           => 'nullable|exists:auto_baza.users,id',
            'status_id'              => 'required|exists:auto_baza.rental_statuses,id',
            'start_datetime'         => 'required|date_format:Y-m-d|after_or_equal:today',
            'end_datetime'           => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $validated = $validator->validated();
        $start = Carbon::createFromFormat('Y-m-d', $validated['start_datetime']);
        $end   = Carbon::createFromFormat('Y-m-d', $validated['end_datetime']);

        if ($end->lte($start)) {
            return $this->error('Validation error', 422, [
                'end_datetime' => ['The end_datetime must be later than the start_datetime.'],
            ]);
        }

        $rental = Rental::create([
            'performer_transport_id' => $validated['performer_transport_id'],
            'performer_id'           => $validated['performer_id'] ?? null,
            'manager_id'             => auth()->id(),
            'status_id'              => $validated['status_id'],
            'start_datetime'         => $start,
            'end_datetime'           => $end,
        ]);

        return $this->success($rental, 'Аренда создана', 201);
    }

    public function show(int $id)
    {
        $rental = Rental::with(['car.model_car.brand', 'client', 'manager', 'status'])->find($id);
        if (!$rental) {
            return $this->error('Аренда не найдена', 404);
        }
        return $this->success($rental);
    }

    public function update(Request $request, int $id)
    {
        $rental = Rental::find($id);
        if (!$rental) {
            return $this->error('Аренда не найдена', 404);
        }

        $validator = Validator::make($request->all(), [
            'status_id'    => 'sometimes|exists:auto_baza.rental_statuses,id',
            'end_datetime' => 'sometimes|date_format:Y-m-d|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $validated = $validator->validated();

        if (isset($validated['end_datetime']) && Carbon::parse($validated['end_datetime'])->lte($rental->start_datetime)) {
            return $this->error('Validation error', 422, [
                'end_datetime' => ['The end_datetime must be later than the start_datetime.'],
            ]);
        }

        $rental->update($validated);
        return $this->success($rental);
    }

    public function destroy(int $id)
    {
        $rental = Rental::find($id);
        if (!$rental) {
            return $this->error('Аренда не найдена', 404);
        }
        $rental->delete();
        return $this->success(null, 'Аренда удалена');
    }
}
