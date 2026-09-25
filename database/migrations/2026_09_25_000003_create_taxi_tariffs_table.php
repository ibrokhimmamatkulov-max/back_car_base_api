<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Тариф аренды под такси (решение от 25.09.2026): платформа переходит
 * целиком на этот один вид объявления, посуточная общая аренда с формы
 * подачи убирается.
 *
 * Новая отдельная таблица, а не переиспользование старых rental_tariffs +
 * car_rental_tariff — та пара таблиц обслуживает старый админский CRUD
 * (/rental-tariffs), трогать её означало бы либо сломать этот CRUD, либо
 * запутать одну таблицу двумя разными смыслами колонок. У этой — один
 * тариф на объявление, без pivot: «предложение только одного тарифа»
 * было решено ещё раньше и здесь просто выражено схемой напрямую.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxi_tariffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performer_transport_id')->unique();

            // Фиксированный выбор, не свободный ввод — проверяется в
            // ListingRequest (in:3,4,6), здесь просто integer.
            $table->unsignedTinyInteger('min_months');
            $table->unsignedTinyInteger('off_days_per_month');
            $table->decimal('price_per_day', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxi_tariffs');
    }
};
