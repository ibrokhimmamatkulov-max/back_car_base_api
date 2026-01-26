<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental_tariffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performer_transport_id');
            $table->integer('duration_days');
            $table->double("price")->default(0);
            $table->boolean('free_weekend_day')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_tariffs');
    }
};
