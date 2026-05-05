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
        Schema::connection('auto_baza')->table('performer_transports', function (Blueprint $table) {
            $table->foreignId('gearbox_id')->nullable();
            $table->unsignedInteger('min_rent_days')->default(1);
            $table->foreignId('city_id')->nullable();
            $table->string('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('auto_baza')->table('performer_transports', function (Blueprint $table) {
            $table->dropForeign(['gearbox_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['gearbox_id','min_rent_days','city_id','address']);
        });
    }
};
