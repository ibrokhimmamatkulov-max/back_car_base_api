<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Календарь занятости. Декларативный: влияет на фильтр по датам и показывается
 * в карточке, но НИЧЕГО не блокирует — заявка на занятые даты принимается.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('listing_unavailable_periods')) {
            return;
        }

        Schema::create('listing_unavailable_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('performer_transport_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->string('comment', 255)->nullable();
            $table->timestamps();

            $table->index(['performer_transport_id', 'date_from', 'date_to'], 'lup_listing_dates_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_unavailable_periods');
    }
};
