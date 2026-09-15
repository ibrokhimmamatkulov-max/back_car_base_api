<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ступени цены по длительности — ценообразование для listing_type = general.
 * Таксопарковая модель (rental_tariffs + car_rental_tariff) остаётся нетронутой
 * и продолжает обслуживать listing_type = taxi.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rental_price_tiers')) {
            return;
        }

        Schema::create('rental_price_tiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('performer_transport_id');
            $table->unsignedInteger('min_days');
            $table->unsignedInteger('max_days')->nullable();
            $table->decimal('price_per_day', 10, 2);
            $table->timestamps();

            $table->index(['performer_transport_id', 'min_days'], 'rpt_listing_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_price_tiers');
    }
};
