<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performer_transport_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('performer_transport_id')->nullable();
            $table->unsignedBigInteger('option_id')->nullable();
            $table->timestamps();
            $table->boolean('is_check')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performer_transport_options');
    }
};
