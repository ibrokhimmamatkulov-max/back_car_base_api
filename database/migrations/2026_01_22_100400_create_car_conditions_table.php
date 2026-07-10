<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('car_conditions')) {
            return;
        }

        Schema::create('car_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->boolean('is_active')->nullable();
            $table->integer('level')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_conditions');
    }
};
