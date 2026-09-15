<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

/**
 * Ничего не отправляет, пишет сообщение в лог. Локальная разработка.
 */
class LogSmsGateway implements SmsGateway
{
    public function send(string $phone, string $message): SmsResult
    {
        Log::info('[SMS:log] Сообщение не отправлено, драйвер — заглушка.', [
            'phone'   => $phone,
            'message' => $message,
        ]);

        return SmsResult::skipped('log');
    }

    public function delivers(): bool
    {
        return false;
    }
}
