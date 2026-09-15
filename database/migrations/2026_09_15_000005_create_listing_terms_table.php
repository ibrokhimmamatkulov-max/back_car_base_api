<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Условия сдачи в аренду, 1:1 с объявлением.
 * Вынесены из performer_transports, чтобы не раздувать легаси-таблицу.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('listing_terms')) {
            return;
        }

        Schema::create('listing_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('performer_transport_id')->unique();

            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->enum('deposit_return_policy', ['on_return', 'daily', 'none'])->default('on_return');
            $table->decimal('deposit_daily_return', 10, 2)->nullable();

            $table->unsignedInteger('mileage_limit_per_day')->nullable();
            $table->decimal('overmileage_price', 10, 2)->nullable();

            $table->enum('fuel_policy', ['full_to_full', 'tenant', 'owner'])->default('full_to_full');

            $table->unsignedTinyInteger('min_driver_age')->nullable();
            $table->unsignedTinyInteger('min_driver_experience')->nullable();
            $table->enum('documents_pledge', ['none', 'passport', 'any_id'])->default('none');
            $table->boolean('require_clean_record')->default(false);

            $table->boolean('allow_taxi')->default(false);
            $table->boolean('allow_intercity')->default(true);
            $table->boolean('allow_abroad')->default(false);
            $table->boolean('allow_smoking')->default(false);
            $table->boolean('allow_pets')->default(false);

            $table->boolean('delivery_available')->default(false);
            $table->decimal('delivery_price', 10, 2)->nullable();

            $table->text('additional_terms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_terms');
    }
};
