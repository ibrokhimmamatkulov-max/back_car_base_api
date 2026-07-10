<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->string('description')->nullable();
            $table->boolean('is_active')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_cars');
    }
};
