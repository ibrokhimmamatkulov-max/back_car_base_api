<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Технические поля, принятые в местных досках объявлений.
 *
 * Растаможка и лицензия на такси — специфика Таджикистана: без них
 * объявление выглядит неполным для здешнего покупателя.
 *
 * vin_verified — признак пройденной проверки техпаспорта. Сами документы
 * лежат в owner_documents и в объявлении не показываются, наружу уходит
 * только бейдж.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('performer_transports', function (Blueprint $table) {
            if (!Schema::hasColumn('performer_transports', 'customs_cleared')) {
                $table->boolean('customs_cleared')->nullable();
            }
            if (!Schema::hasColumn('performer_transports', 'engine_volume')) {
                $table->decimal('engine_volume', 3, 1)->nullable();
            }
            if (!Schema::hasColumn('performer_transports', 'mileage')) {
                $table->unsignedInteger('mileage')->nullable();
            }
            if (!Schema::hasColumn('performer_transports', 'drive_type')) {
                $table->enum('drive_type', ['fwd', 'rwd', 'awd'])->nullable();
            }
            if (!Schema::hasColumn('performer_transports', 'has_taxi_license')) {
                $table->boolean('has_taxi_license')->default(false);
            }
            if (!Schema::hasColumn('performer_transports', 'has_turbo')) {
                $table->boolean('has_turbo')->default(false);
            }
            if (!Schema::hasColumn('performer_transports', 'vin_verified')) {
                $table->boolean('vin_verified')->default(false);
            }
            if (!Schema::hasColumn('performer_transports', 'vin_verified_at')) {
                $table->timestamp('vin_verified_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('performer_transports', function (Blueprint $table) {
            $columns = array_filter([
                'customs_cleared', 'engine_volume', 'mileage', 'drive_type',
                'has_taxi_license', 'has_turbo', 'vin_verified', 'vin_verified_at',
            ], fn ($c) => Schema::hasColumn('performer_transports', $c));

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
