<?php

namespace App\Services\Listing;

use App\Models\ListingUnavailablePeriod;
use Illuminate\Database\Eloquent\Builder;

/**
 * Календарь занятости.
 *
 * Используется только для фильтрации витрины и показа в карточке.
 * Заявку не блокирует — это осознанное решение (ТЗ §2): системы бронирования
 * в проекте нет, пересечения разруливает владелец.
 */
class AvailabilityService
{
    /**
     * Исключает из выборки объявления, занятые хотя бы на один день интервала.
     */
    public function excludeBusy(Builder $query, ?string $from, ?string $to): Builder
    {
        if (!$from || !$to) {
            return $query;
        }

        return $query->whereNotExists(function ($sub) use ($from, $to) {
            $sub->selectRaw(1)
                ->from('listing_unavailable_periods as lup')
                ->whereColumn('lup.performer_transport_id', 'performer_transports.id')
                ->where('lup.date_from', '<=', $to)
                ->where('lup.date_to', '>=', $from);
        });
    }

    public function isBusy(int $listingId, string $from, string $to): bool
    {
        return ListingUnavailablePeriod::where('performer_transport_id', $listingId)
            ->where('date_from', '<=', $to)
            ->where('date_to', '>=', $from)
            ->exists();
    }

    /**
     * Проверяет, что новый период не накладывается на уже существующие.
     */
    public function overlapsExisting(int $listingId, string $from, string $to, ?int $exceptId = null): bool
    {
        return ListingUnavailablePeriod::where('performer_transport_id', $listingId)
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->where('date_from', '<=', $to)
            ->where('date_to', '>=', $from)
            ->exists();
    }
}
