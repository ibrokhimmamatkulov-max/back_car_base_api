<?php

namespace App\Services\Sms;

class SmsResult
{
    public function __construct(
        public readonly bool $delivered,
        public readonly string $driver,
        public readonly ?string $error = null,
    ) {
    }

    public static function sent(string $driver): self
    {
        return new self(true, $driver);
    }

    public static function skipped(string $driver): self
    {
        return new self(false, $driver);
    }

    public static function failed(string $driver, string $error): self
    {
        return new self(false, $driver, $error);
    }
}
