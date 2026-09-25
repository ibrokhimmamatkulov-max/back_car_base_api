<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\CarCondition;
use App\Models\CarOption;
use Illuminate\Http\JsonResponse;

/**
 * Справочники для формы подачи/правки объявления, которые не подходят
 * под общие /landing/* (это не выбор из каталога, а сами варианты полей
 * ListingRequest: состояние, привод, объём двигателя, год, доп. опции).
 *
 * Раньше фронт уже дёргал эту ручку, а на бэке её не было вовсе —
 * блок «Автомобиль» формы не мог заполниться в боевом режиме.
 */
class ReferenceController extends Controller
{
    /** Совпадает с ListingRequest: engine_volume — numeric 0.1–9.9 */
    private const ENGINE_VOLUMES = [1.0, 1.2, 1.4, 1.5, 1.6, 1.8, 2.0, 2.4, 2.5, 3.0, 3.5, 4.0];

    /** Совпадает с ListingRequest: drive_type — in:fwd,rwd,awd (хранится строкой, справочной таблицы нет) */
    private const DRIVE_TYPES = [
        ['id' => 'fwd', 'name' => 'Передний'],
        ['id' => 'rwd', 'name' => 'Задний'],
        ['id' => 'awd', 'name' => 'Полный'],
    ];

    public function index(): JsonResponse
    {
        return $this->success([
            'conditions' => CarCondition::query()
                ->orderBy('level')
                ->get(['id', 'name'])
                ->values(),

            'drive_types' => self::DRIVE_TYPES,

            'engine_volumes' => collect(self::ENGINE_VOLUMES)
                ->map(fn ($v) => ['id' => $v, 'name' => number_format($v, 1) . ' л'])
                ->values(),

            // Совпадает с ListingRequest: year_of_issue — min:1950, max:текущий+1
            'years' => collect(range((int) date('Y') + 1, 1950))
                ->map(fn ($y) => ['id' => $y, 'name' => (string) $y])
                ->values(),

            // Доп. опции машины (кондиционер, камера и т.п.) — те же car_options,
            // что показывает карточка объявления, но без привязки к типу топлива
            // (у тех model = 'car_fuel_type', у этих — null).
            'dop_options' => CarOption::query()
                ->whereNull('model')
                ->where('is_active', 1)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->values(),
        ]);
    }
}
