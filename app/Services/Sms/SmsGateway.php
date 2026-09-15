<?php

namespace App\Services\Sms;

interface SmsGateway
{
    public function send(string $phone, string $message): SmsResult;

    /**
     * Реально ли сообщение уходит адресату.
     * false у заглушек — по этому признаку OtpService решает,
     * нужно ли раскрывать код в ответе API.
     */
    public function delivers(): bool;
}
