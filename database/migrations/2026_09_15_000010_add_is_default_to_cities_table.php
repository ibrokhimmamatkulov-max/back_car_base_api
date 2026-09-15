<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('cities', 'is_default')) {
            Schema::table('cities', function (Blueprint $table) {
                $table->boolean('is_default')->default(false);
            });
        }

        if (DB::table('cities')->where('is_default', true)->exists()) {
            return;
        }

        // По ТЗ город по умолчанию — Худжанд. Написание в справочнике может
        // отличаться (Худжанд / Хучанд / Khujand), поэтому ищем по подстроке,
        // а при неудаче честно падаем на первый по сортировке и пишем в лог.
        $city = DB::table('cities')->where('name', 'like', '%уджанд%')->first()
            ?? DB::table('cities')->where('name', 'like', '%учанд%')->first()
            ?? DB::table('cities')->where('name', 'like', '%hujand%')->first();

        if (!$city) {
            $city = DB::table('cities')->orderBy('sort')->orderBy('id')->first();
            Log::warning('Миграция is_default: Худжанд не найден в справочнике городов. '
                . 'Городом по умолчанию назначен «' . ($city->name ?? 'нет городов') . '». '
                . 'Проверьте вручную.');
        }

        if ($city) {
            DB::table('cities')->where('id', $city->id)->update(['is_default' => true]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cities', 'is_default')) {
            Schema::table('cities', function (Blueprint $table) {
                $table->dropColumn('is_default');
            });
        }
    }
};
