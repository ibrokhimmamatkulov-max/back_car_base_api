<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('owners')) {
            return;
        }

        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->unique();
            $table->string('login', 60)->unique();
            $table->string('password');
            $table->string('first_name', 80);
            $table->string('last_name', 80)->nullable();
            $table->string('middle_name', 80)->nullable();
            $table->enum('owner_type', ['individual', 'company'])->default('individual');
            $table->string('company_name', 180)->nullable();
            $table->string('tin', 30)->nullable();
            $table->string('email', 120)->nullable();
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->text('blocked_reason')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
