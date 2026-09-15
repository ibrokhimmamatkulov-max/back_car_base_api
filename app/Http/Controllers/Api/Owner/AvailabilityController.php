<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\ListingUnavailablePeriod;
use App\Services\Listing\AvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvailabilityController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availability,
    ) {
    }

    public function index(Request $request, int $id): JsonResponse
    {
        $periods = ListingUnavailablePeriod::where('performer_transport_id', $id)
            ->orderBy('date_from')
            ->get()
            ->map(fn ($p) => [
                'id'        => $p->id,
                'date_from' => $p->date_from?->toDateString(),
                'date_to'   => $p->date_to?->toDateString(),
                'comment'   => $p->comment,
            ]);

        return $this->success($periods);
    }

    public function store(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date_from' => 'required|date_format:Y-m-d',
            'date_to'   => 'required|date_format:Y-m-d|after_or_equal:date_from',
            'comment'   => 'nullable|string|max:255',
        ], [
            'date_to.after_or_equal' => 'Дата окончания не может быть раньше даты начала.',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $data = $validator->validated();

        if ($this->availability->overlapsExisting($id, $data['date_from'], $data['date_to'])) {
            return $this->error('Этот период пересекается с уже отмеченным.', 422, [
                'date_from' => ['Период пересекается с существующим.'],
            ]);
        }

        $period = ListingUnavailablePeriod::create($data + ['performer_transport_id' => $id]);

        return $this->success([
            'id'        => $period->id,
            'date_from' => $period->date_from?->toDateString(),
            'date_to'   => $period->date_to?->toDateString(),
            'comment'   => $period->comment,
        ], 'Период занятости добавлен.', 201);
    }

    public function destroy(Request $request, int $id, int $periodId): JsonResponse
    {
        $period = ListingUnavailablePeriod::where('performer_transport_id', $id)
            ->where('id', $periodId)
            ->first();

        if (!$period) {
            return $this->error('Период не найден.', 404);
        }

        $period->delete();

        return $this->success(null, 'Период удалён.');
    }
}
