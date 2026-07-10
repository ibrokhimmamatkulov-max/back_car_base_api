<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_applications', function (Blueprint $table) {
            $table->string('name')->after('phone');
            $table->text('comment')->nullable()->after('name');
            $table->unsignedBigInteger('rental_tariff_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rental_applications', function (Blueprint $table) {
            $table->dropColumn(['name', 'comment']);
            $table->unsignedBigInteger('rental_tariff_id')->nullable(false)->change();
        });
    }
};
