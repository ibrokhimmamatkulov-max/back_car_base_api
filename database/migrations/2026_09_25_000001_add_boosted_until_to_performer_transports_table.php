<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Платный подъём объявления в топ выдачи (решение от 25.09.2026).
 *
 * Один nullable timestamp, а не отдельный boolean + отдельное поле срока:
 * NULL уже и есть «не поднято», а «поднято до X» сразу несёт и признак,
 * и срок действия одним значением. Снять поднятие — просто обнулить.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('performer_transports', function (Blueprint $table) {
            if (!Schema::hasColumn('performer_transports', 'boosted_until')) {
                $table->timestamp('boosted_until')->nullable()->after('published_at');
                $table->index('boosted_until');
            }
        });
    }

    public function down(): void
    {
        Schema::table('performer_transports', function (Blueprint $table) {
            if (Schema::hasColumn('performer_transports', 'boosted_until')) {
                $table->dropIndex(['boosted_until']);
                $table->dropColumn('boosted_until');
            }
        });
    }
};
