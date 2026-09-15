<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Идемпотентный сидер статусов заявок.
 * Существующие записи не трогает — обновляет только name по известному code.
 */
return new class extends Migration
{
    private const STATUSES = [
        ['code' => 'new',       'name' => 'Новая'],
        ['code' => 'contacted', 'name' => 'Связались'],
        ['code' => 'deal',      'name' => 'Сделка'],
        ['code' => 'rejected',  'name' => 'Отказ'],
        ['code' => 'spam',      'name' => 'Спам'],
    ];

    public function up(): void
    {
        foreach (self::STATUSES as $status) {
            $exists = DB::table('application_statuses')->where('code', $status['code'])->exists();

            if ($exists) {
                continue;
            }

            DB::table('application_statuses')->insert($status + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Статусы могут быть уже проставлены боевым заявкам — не удаляем.
    }
};
