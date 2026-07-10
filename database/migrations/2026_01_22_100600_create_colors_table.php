<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('colors')) {
            return;
        }

        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_for_sms')->nullable();
            $table->string('name_tj')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->boolean('is_active')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
