<?php

namespace App\Console\Commands;

use App\Models\PerformerTransport;
use App\Services\Listing\ModerationService;
use Illuminate\Console\Command;

/**
 * Переводит просроченные объявления (30 дней с публикации, см.
 * config('listing.lifetime_days')) в статус archived.
 *
 * Витрина скрывает просроченное сама, через scopeVisibleOnShowcase — эта
 * команда нужна не для того, чтобы объявление пропало с сайта (это уже
 * произошло), а чтобы владелец увидел в кабинете «В архиве» вместо того,
 * чтобы гадать, почему объявление просто перестало быть видно, и чтобы
 * остался след в ListingModerationLog.
 *
 * Требует рабочий `php artisan schedule:run` по крону (стандартное
 * требование Laravel). Без него объявление всё равно скрыто с витрины —
 * страдает только кабинет владельца, не публичная сторона.
 */
class ExpireListings extends Command
{
    protected $signature = 'listings:expire';

    protected $description = 'Архивирует объявления старше срока жизни (config listing.lifetime_days)';

    public function handle(ModerationService $moderation): int
    {
        $days = (int) config('listing.lifetime_days', 30);

        $expired = PerformerTransport::query()
            ->where('moderation_status', PerformerTransport::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<', now()->subDays($days))
            ->get();

        foreach ($expired as $listing) {
            $moderation->archive($listing, 'Истёк срок размещения (' . $days . ' дн.)');
        }

        $this->info("Архивировано просроченных объявлений: {$expired->count()}.");

        return self::SUCCESS;
    }
}
