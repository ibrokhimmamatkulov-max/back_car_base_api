<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Документы владельца и на авто.
 * `type` — строка, а не enum: состав обязательных документов ещё не определён
 * (см. ТЗ, открытый вопрос №1), список должен расширяться без миграции.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('owner_documents')) {
            return;
        }

        Schema::create('owner_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('performer_transport_id')->nullable();
            $table->string('type', 40);
            $table->string('path');
            $table->string('original_name');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['owner_id', 'status']);
            $table->index('performer_transport_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner_documents');
    }
};
