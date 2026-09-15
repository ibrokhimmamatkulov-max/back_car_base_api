<?php

namespace App\Services\Owner;

use App\Models\Owner;
use App\Services\Sms\SmsGateway;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OwnerRegistrationService
{
    public function __construct(
        private readonly SmsGateway $sms,
        private readonly OtpService $otp,
    ) {
    }

    public function exists(string $phone): bool
    {
        return Owner::where('phone', PhoneNormalizer::normalize($phone))->exists();
    }

    public function findByPhone(string $phone): ?Owner
    {
        return Owner::where('phone', PhoneNormalizer::normalize($phone))->first();
    }

    /**
     * Создаёт аккаунт после подтверждения телефона и доставляет учётные данные.
     *
     * @return array{owner: Owner, credentials: ?array{login: string, password: string}}
     */
    public function register(string $phone, ?string $firstName = null): array
    {
        $phone = PhoneNormalizer::normalize($phone);

        $login    = $this->generateLogin($phone);
        $password = $this->generatePassword();

        $owner = Owner::create([
            'phone'             => $phone,
            'login'             => $login,
            'password'          => Hash::make($password),
            'first_name'        => $firstName ?: 'Владелец',
            'status'            => Owner::STATUS_ACTIVE,
            'phone_verified_at' => now(),
        ]);

        $this->sms->send($phone, strtr(config('sms.templates.credentials'), [
            ':login'    => $login,
            ':password' => $password,
        ]));

        // В демо-режиме SMS не уходит, поэтому учётку показываем на экране.
        // В боевом — не раскрываем: она уже отправлена вторым сообщением.
        return [
            'owner'       => $owner,
            'credentials' => $this->otp->isStubMode()
                ? ['login' => $login, 'password' => $password]
                : null,
        ];
    }

    /**
     * Новый пароль взамен сгенерированного или забытого.
     *
     * @return array{password: ?string}
     */
    public function resetPassword(Owner $owner): array
    {
        $password = $this->generatePassword();

        $owner->update([
            'password'            => Hash::make($password),
            'password_changed_at' => null,
        ]);

        $this->sms->send($owner->phone, strtr(config('sms.templates.credentials'), [
            ':login'    => $owner->login,
            ':password' => $password,
        ]));

        return ['password' => $this->otp->isStubMode() ? $password : null];
    }

    private function generateLogin(string $phone): string
    {
        $base = 'g' . substr($phone, -9);

        if (!Owner::where('login', $base)->exists()) {
            return $base;
        }

        do {
            $candidate = $base . random_int(10, 99);
        } while (Owner::where('login', $candidate)->exists());

        return $candidate;
    }

    /**
     * Пароль случайный и не производный от телефона — см. ТЗ §7.4.
     * Символы, которые легко спутать при чтении с экрана, исключены.
     */
    private function generatePassword(): string
    {
        $alphabet = 'abcdefghijkmnpqrstuvwxyz23456789';
        $password = '';

        for ($i = 0; $i < 8; $i++) {
            $password .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return $password;
    }
}
