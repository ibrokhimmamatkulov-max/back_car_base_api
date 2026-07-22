<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_rental_tariff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performer_transport_id');
            $table->foreignId('rental_tariff_id');
            $table->timestamps();

            $table->unique(['performer_transport_id', 'rental_tariff_id']);
        });

        DB::table('rental_tariffs')->whereNotNull('performer_transport_id')->orderBy('id')
            ->each(function ($tariff) {
                DB::table('car_rental_tariff')->insert([
                    'performer_transport_id' => $tariff->performer_transport_id,
                    'rental_tariff_id'       => $tariff->id,
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ]);
            });

        Schema::table('rental_tariffs', function (Blueprint $table) {
            $table->dropColumn('performer_transport_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_tariffs', function (Blueprint $table) {
            $table->foreignId('performer_transport_id')->nullable();
        });

        DB::table('car_rental_tariff')->orderBy('id')->each(function ($pivot) {
            DB::table('rental_tariffs')->where('id', $pivot->rental_tariff_id)
                ->update(['performer_transport_id' => $pivot->performer_transport_id]);
        });

        Schema::dropIfExists('car_rental_tariff');
    }
};
