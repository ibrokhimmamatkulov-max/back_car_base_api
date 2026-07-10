<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('divisions')) {
            return;
        }

        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->boolean('is_active')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('polygon_id')->nullable();
            $table->string('type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
