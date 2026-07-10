<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_car_id')->nullable();
            $table->string('allowance_id')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps();
            $table->string('model')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_options');
    }
};
