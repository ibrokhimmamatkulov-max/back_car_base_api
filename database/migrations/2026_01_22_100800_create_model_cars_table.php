<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('model_cars')) {
            return;
        }

        Schema::create('model_cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_car_id')->nullable();
            $table->unsignedBigInteger('class_car_id')->nullable();
            $table->timestamps();
            $table->integer('car_seat_from')->nullable();
            $table->integer('car_seat_before')->nullable();
            $table->boolean('is_active')->nullable();
            $table->unsignedBigInteger('car_brand_id')->nullable();
            $table->string('car_model')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_cars');
    }
};
