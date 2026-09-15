<?php

namespace App\Services\Owner;

/**
 * Приводит телефон к единому виду 992XXXXXXXXX.
 * Нужен потому, что телефон — уникальный ключ владельца: «+992 90 123-45-67»,
 * «992901234567» и «901234567» обязаны быть одним и тем же аккаунтом.
 */
class PhoneNormalizer
{
    private const COUNTRY_CODE = '992';
    private const NATIONAL_LENGTH = 9;

    public static function normalize(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        // 8XXXXXXXXX — местный формат с ведущей восьмёркой
        if (strlen($digits) === self::NATIONAL_LENGTH + 1 && str_starts_with($digits, '8')) {
            $digits = substr($digits, 1);
        }

        if (strlen($digits) === self::NATIONAL_LENGTH) {
            return self::COUNTRY_CODE . $digits;
        }

        return $digits;
    }

    public static function isValid(string $phone): bool
    {
        $normalized = self::normalize($phone);

        return strlen($normalized) === strlen(self::COUNTRY_CODE) + self::NATIONAL_LENGTH
            && str_starts_with($normalized, self::COUNTRY_CODE);
    }

    public static function mask(string $phone): string
    {
        $normalized = self::normalize($phone);

        if (strlen($normalized) < 6) {
            return $normalized;
        }

        return substr($normalized, 0, 5) . str_repeat('*', strlen($normalized) - 7)
            . substr($normalized, -2);
    }
}
