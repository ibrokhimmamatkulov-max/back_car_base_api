<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_car_id')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_cars');
    }
};
