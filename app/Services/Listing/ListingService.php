<?php

namespace App\Services\Listing;

use App\Models\ListingTerms;
use App\Models\Owner;
use App\Models\PerformerTransport;
use App\Models\RentalPriceTier;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Создание и обновление объявления одной транзакцией:
 * машина + условия + ступени цены. Частично созданное объявление —
 * худший из возможных исходов, поэтому всё или ничего.
 */
class ListingService
{
    private const CAR_FIELDS = [
        'car_model_id', 'body_type_id', 'condition_id', 'color_id', 'year_of_issue',
        'count_seat', 'car_number', 'fuel_type_id', 'city_id', 'gearbox_id',
        'min_rent_days', 'max_rent_days', 'address', 'title', 'description', 'dop_info',
    ];

    public function create(Owner $owner, array $data): PerformerTransport
    {
        $this->assertPriceTiers($data);
        $this->assertPlateIsFree($data['car_number'] ?? null);

        return DB::transaction(function () use ($owner, $data) {
            $listing = PerformerTransport::create(
                $this->carAttributes($data) + [
                    'owner_id'          => $owner->id,
                    'listing_type'      => PerformerTransport::TYPE_GENERAL,
                    'source'            => PerformerTransport::SOURCE_OWNER,
                    'moderation_status' => PerformerTransport::STATUS_PENDING,
                    'submitted_at'      => now(),
                    'active'            => PerformerTransport::ACTIVE,
                ]
            );

            $this->syncTerms($listing, $data['terms'] ?? []);
            $this->syncPriceTiers($listing, $data['price_tiers'] ?? []);

            return $listing->load(['terms', 'priceTiers']);
        });
    }

    /**
     * @return array{listing: PerformerTransport, returned_to_moderation: bool}
     */
    public function update(PerformerTransport $listing, array $data): array
    {
        if (array_key_exists('price_tiers', $data)) {
            $this->assertPriceTiers($data + ['min_rent_days' => $listing->min_rent_days]);
        }

        if (!empty($data['car_number'])) {
            $this->assertPlateIsFree($data['car_number'], $listing->id);
        }

        return DB::transaction(function () use ($listing, $data) {
            $attributes = $this->carAttributes($data);
            $significant = $this->touchesSignificantFields($listing, $attributes, $data);

            $listing->fill($attributes);

            // Правка существенных полей у опубликованного объявления возвращает
            // его на модерацию; старая редакция остаётся видимой до решения.
            $returned = false;
            if ($significant && $listing->moderation_status === PerformerTransport::STATUS_PUBLISHED) {
                $listing->moderation_status = PerformerTransport::STATUS_PENDING;
                $listing->submitted_at = now();
                $returned = true;
            }

            $listing->save();

            if (array_key_exists('terms', $data)) {
                $this->syncTerms($listing, $data['terms'] ?? []);
            }

            if (array_key_exists('price_tiers', $data)) {
                $this->syncPriceTiers($listing, $data['price_tiers'] ?? []);
            }

            return [
                'listing'                => $listing->load(['terms', 'priceTiers']),
                'returned_to_moderation' => $returned,
            ];
        });
    }

    /**
     * Готово ли объявление к публикации (ТЗ §9.2).
     *
     * @return array<int, string>
     */
    public function publishBlockers(PerformerTransport $listing): array
    {
        $blockers = [];

        if ($listing->photos()->count() === 0) {
            $blockers[] = 'Добавьте хотя бы одну фотографию.';
        }

        if ($listing->isGeneral() && $listing->priceTiers()->count() === 0) {
            $blockers[] = 'Задайте хотя бы одну ступень цены.';
        }

        return $blockers;
    }

    private function carAttributes(array $data): array
    {
        return array_intersect_key($data, array_flip(self::CAR_FIELDS));
    }

    private function touchesSignificantFields(PerformerTransport $listing, array $attributes, array $data): bool
    {
        foreach (PerformerTransport::SIGNIFICANT_FIELDS as $field) {
            if (array_key_exists($field, $attributes)
                && (string) $attributes[$field] !== (string) $listing->getOriginal($field)) {
                return true;
            }
        }

        // Цены и условия — тоже существенные.
        return array_key_exists('price_tiers', $data) || array_key_exists('terms', $data);
    }

    private function syncTerms(PerformerTransport $listing, array $terms): void
    {
        ListingTerms::updateOrCreate(
            ['performer_transport_id' => $listing->id],
            $terms
        );
    }

    private function syncPriceTiers(PerformerTransport $listing, array $tiers): void
    {
        RentalPriceTier::where('performer_transport_id', $listing->id)->delete();

        foreach ($tiers as $tier) {
            RentalPriceTier::create([
                'performer_transport_id' => $listing->id,
                'min_days'               => (int) $tier['min_days'],
                'max_days'               => isset($tier['max_days']) && $tier['max_days'] !== null
                    ? (int) $tier['max_days']
                    : null,
                'price_per_day'          => $tier['price_per_day'],
            ]);
        }
    }

    private function assertPriceTiers(array $data): void
    {
        $errors = PriceTierValidator::validate(
            $data['price_tiers'] ?? [],
            (int) ($data['min_rent_days'] ?? 1)
        );

        if ($errors) {
            throw ValidationException::withMessages(['price_tiers' => $errors]);
        }
    }

    /**
     * Одну машину нельзя выставить дважды (ТЗ §9.3).
     * Архивные и удалённые объявления номер не занимают.
     */
    private function assertPlateIsFree(?string $plate, ?int $exceptId = null): void
    {
        if (!$plate) {
            return;
        }

        $taken = PerformerTransport::query()
            ->where('car_number', $plate)
            ->whereNotIn('moderation_status', [PerformerTransport::STATUS_ARCHIVED])
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->exists();

        if ($taken) {
            throw ValidationException::withMessages([
                'car_number' => ['Машина с таким госномером уже размещена. '
                    . 'Если это ваше объявление, найдите его в кабинете; если нет — обратитесь в поддержку.'],
            ]);
        }
    }
}
