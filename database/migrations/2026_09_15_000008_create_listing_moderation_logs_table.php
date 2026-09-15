<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Журнал решений модерации.
 * FK на users намеренно нет: таблица users живёт в другой базе (mysql_taxi),
 * а MySQL не поддерживает внешние ключи между базами.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('listing_moderation_logs')) {
            return;
        }

        Schema::create('listing_moderation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('performer_transport_id');
            $table->unsignedBigInteger('moderator_id')->nullable();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['performer_transport_id', 'created_at'], 'lml_listing_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_moderation_logs');
    }
};
