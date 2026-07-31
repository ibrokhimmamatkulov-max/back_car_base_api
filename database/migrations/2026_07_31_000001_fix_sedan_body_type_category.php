<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('body_types')
            ->where('name', 'седан')
            ->where('category_car_id', 1)
            ->update(['category_car_id' => 2]);
    }

    public function down(): void
    {
        DB::table('body_types')
            ->where('name', 'седан')
            ->where('category_car_id', 2)
            ->update(['category_car_id' => 1]);
    }
};
