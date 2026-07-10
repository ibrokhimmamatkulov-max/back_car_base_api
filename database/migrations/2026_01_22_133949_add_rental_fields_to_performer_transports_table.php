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
    Schema::table('performer_transports', function (Blueprint $table) {
        if (!Schema::hasColumn('performer_transports', 'gearbox_id')) {
            $table->foreignId('gearbox_id')->nullable();
        }
        if (!Schema::hasColumn('performer_transports', 'min_rent_days')) {
            $table->unsignedInteger('min_rent_days')->default(1);
        }
        if (!Schema::hasColumn('performer_transports', 'city_id')) {
            $table->foreignId('city_id')->nullable();
        }
        if (!Schema::hasColumn('performer_transports', 'address')) {
            $table->string('address')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('performer_transports', function (Blueprint $table) {
            $table->dropForeign(['gearbox_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['gearbox_id','min_rent_days','city_id','address']);
        });
    }
};
