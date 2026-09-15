<?php

namespace App\Services\Listing;

/**
 * Проверяет непротиворечивость ступеней цены.
 *
 * Правила (ТЗ §5.1): ступени не пересекаются, начинаются с минимального срока
 * аренды, идут без дыр, ровно одна может быть открытой сверху и она последняя.
 *
 * Проверка живёт в коде, а не в БД: выразить «без дыр» ограничением таблицы
 * нельзя, а разносить правило между двумя местами — верный способ его расщепить.
 */
class PriceTierValidator
{
    /**
     * @param  array<int, array{min_days: int, max_days: int|null, price_per_day: float|int}>  $tiers
     * @return array<int, string>  список ошибок, пустой — значит всё в порядке
     */
    public static function validate(array $tiers, int $minRentDays = 1): array
    {
        $errors = [];

        if (empty($tiers)) {
            return ['Нужна хотя бы одна ступень цены.'];
        }

        foreach ($tiers as $i => $tier) {
            $n = $i + 1;

            if (!isset($tier['min_days']) || (int) $tier['min_days'] < 1) {
                $errors[] = "Ступень {$n}: начало диапазона должно быть не меньше 1 дня.";
            }

            if (!isset($tier['price_per_day']) || (float) $tier['price_per_day'] <= 0) {
                $errors[] = "Ступень {$n}: цена должна быть больше нуля.";
            }

            if (isset($tier['max_days']) && $tier['max_days'] !== null
                && (int) $tier['max_days'] < (int) ($tier['min_days'] ?? 0)) {
                $errors[] = "Ступень {$n}: конец диапазона меньше начала.";
            }
        }

        if ($errors) {
            return $errors;
        }

        usort($tiers, fn ($a, $b) => $a['min_days'] <=> $b['min_days']);

        if ((int) $tiers[0]['min_days'] !== $minRentDays) {
            $errors[] = sprintf(
                'Первая ступень должна начинаться с минимального срока аренды (%d дн.), а начинается с %d.',
                $minRentDays,
                (int) $tiers[0]['min_days']
            );
        }

        $openEnded = 0;

        foreach ($tiers as $i => $tier) {
            if ($tier['max_days'] === null) {
                $openEnded++;

                if ($i !== count($tiers) - 1) {
                    $errors[] = 'Ступень без верхней границы может быть только последней.';
                }

                continue;
            }

            $next = $tiers[$i + 1] ?? null;

            if ($next === null) {
                continue;
            }

            $expected = (int) $tier['max_days'] + 1;
            $actual   = (int) $next['min_days'];

            if ($actual < $expected) {
                $errors[] = sprintf(
                    'Ступени %d и %d пересекаются: диапазоны %d–%d и %d–%s.',
                    $i + 1,
                    $i + 2,
                    (int) $tier['min_days'],
                    (int) $tier['max_days'],
                    $actual,
                    $next['max_days'] === null ? '∞' : (int) $next['max_days']
                );
            } elseif ($actual > $expected) {
                $errors[] = sprintf(
                    'Между ступенями %d и %d дыра: не задана цена для срока %d–%d дн.',
                    $i + 1,
                    $i + 2,
                    $expected,
                    $actual - 1
                );
            }
        }

        if ($openEnded === 0) {
            $last = end($tiers);
            $errors[] = sprintf(
                'Последняя ступень должна быть без верхней границы, иначе для срока дольше %d дн. цена не определена.',
                (int) $last['max_days']
            );
        }

        return $errors;
    }
}
