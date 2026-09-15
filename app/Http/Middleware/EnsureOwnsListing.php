<?php

namespace App\Http\Middleware;

use App\Models\PerformerTransport;
use Closure;
use Illuminate\Http\Request;

/**
 * Первый из двух рубежей проверки владения (второй — явная выборка
 * через scopeOwnedBy в контроллерах). Забыть оба сложнее, чем один.
 *
 * Чужое объявление отдаётся как 404, а не 403: иначе перебором id можно
 * узнать, какие объявления существуют.
 */
class EnsureOwnsListing
{
    public function handle(Request $request, Closure $next)
    {
        $owner = $request->user('owner');
        $listingId = $request->route('listing') ?? $request->route('id');

        if ($listingId instanceof PerformerTransport) {
            $listingId = $listingId->id;
        }

        $belongs = PerformerTransport::query()
            ->where('id', $listingId)
            ->where('owner_id', $owner?->id)
            ->exists();

        if (!$belongs) {
            return response()->json([
                'success' => false,
                'code'    => 404,
                'message' => 'Объявление не найдено.',
            ], 404);
        }

        return $next($request);
    }
}
