<?php

namespace App\Services\Listing;

use App\Models\PerformerTransport;
use Carbon\Carbon;

/**
 * Расчёт стоимости аренды по датам.
 *
 * Это калькулятор-ориентир для карточки, не оферта: платежей в системе нет,
 * итоговую цену стороны согласуют между собой (ТЗ §2).
 */
class PriceCalculator
{
    /**
     * @return array{
     *     days: int,
     *     price_per_day: float|null,
     *     tier_id: int|null,
     *     rent_total: float|null,
     *     deposit: float,
     *     delivery: float,
     *     total: float|null,
     *     currency: string,
     *     error: string|null
     * }
     */
    public function calculate(PerformerTransport $listing, ?string $from, ?string $to, bool $withDelivery = false): array
    {
        $empty = [
            'days'          => 0,
            'price_per_day' => null,
            'tier_id'       => null,
            'rent_total'    => null,
            'deposit'       => 0.0,
            'delivery'      => 0.0,
            'total'         => null,
            'currency'      => 'TJS',
            'error'         => null,
        ];

        if (!$from || !$to) {
            return ['error' => 'Не заданы даты аренды.'] + $empty;
        }

        try {
            $start = Carbon::parse($from)->startOfDay();
            $end   = Carbon::parse($to)->startOfDay();
        } catch (\Throwable) {
            return ['error' => 'Некорректный формат дат.'] + $empty;
        }

        if ($end->lte($start)) {
            return ['error' => 'Дата окончания должна быть позже даты начала.'] + $empty;
        }

        $days = (int) $start->diffInDays($end);

        $minDays = (int) ($listing->min_rent_days ?: 1);
        if ($days < $minDays) {
            return ['error' => "Минимальный срок аренды — {$minDays} дн."] + array_merge($empty, ['days' => $days]);
        }

        $maxDays = $listing->max_rent_days ? (int) $listing->max_rent_days : null;
        if ($maxDays !== null && $days > $maxDays) {
            return ['error' => "Максимальный срок аренды — {$maxDays} дн."] + array_merge($empty, ['days' => $days]);
        }

        $tier = $this->resolveTier($listing, $days);

        if (!$tier) {
            return ['error' => 'Для этого срока цена не задана.'] + array_merge($empty, ['days' => $days]);
        }

        $pricePerDay = (float) $tier->price_per_day;
        $rentTotal   = round($pricePerDay * $days, 2);

        $terms    = $listing->relationLoaded('terms') ? $listing->terms : $listing->terms()->first();
        $deposit  = $terms ? (float) $terms->deposit_amount : 0.0;
        $delivery = ($withDelivery && $terms && $terms->delivery_available)
            ? (float) ($terms->delivery_price ?? 0)
            : 0.0;

        return [
            'days'          => $days,
            'price_per_day' => $pricePerDay,
            'tier_id'       => $tier->id,
            'rent_total'    => $rentTotal,
            'deposit'       => $deposit,
            'delivery'      => $delivery,
            'total'         => round($rentTotal + $delivery, 2),
            'currency'      => 'TJS',
            'error'         => null,
        ];
    }

    private function resolveTier(PerformerTransport $listing, int $days)
    {
        $tiers = $listing->relationLoaded('priceTiers')
            ? $listing->priceTiers
            : $listing->priceTiers()->get();

        foreach ($tiers as $tier) {
            if ($tier->covers($days)) {
                return $tier;
            }
        }

        return null;
    }
}
