<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performer_transports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_park_id')->nullable();
            $table->unsignedBigInteger('performer_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('condition_id')->nullable();
            $table->integer('year_of_issue')->nullable();
            $table->string('license_number')->nullable();
            $table->unsignedBigInteger('connected_id')->nullable();
            $table->string('car_number')->nullable();
            $table->boolean('active')->nullable();
            $table->text('legal_entity')->nullable();
            $table->text('dop_info')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('car_model_id')->nullable();
            $table->unsignedBigInteger('body_type_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->string('date_in_office')->nullable();
            $table->string('VIN')->nullable();
            $table->string('STS_N')->nullable();
            $table->text('cargo_properties')->nullable();
            $table->unsignedBigInteger('created_user_id')->nullable();
            $table->unsignedBigInteger('updated_user_id')->nullable();
            $table->integer('count_seat')->nullable();
            $table->string('small_number')->nullable();
            $table->unsignedBigInteger('licensor_id')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('fuel_type_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performer_transports');
    }
};
