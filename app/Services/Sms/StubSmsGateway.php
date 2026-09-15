<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

/**
 * Демо-режим: SMS не отправляется, код возвращается клиенту в ответе API.
 *
 * Каждое использование пишется в лог уровня critical намеренно — чтобы
 * включённый на боевом окружении демо-режим было невозможно не заметить.
 */
class StubSmsGateway implements SmsGateway
{
    public function send(string $phone, string $message): SmsResult
    {
        Log::critical('[SMS:stub] ДЕМО-РЕЖИМ: SMS не отправлено, код раскрыт клиенту.', [
            'phone'   => $phone,
            'message' => $message,
            'env'     => app()->environment(),
        ]);

        return SmsResult::skipped('stub');
    }

    public function delivers(): bool
    {
        return false;
    }
}
