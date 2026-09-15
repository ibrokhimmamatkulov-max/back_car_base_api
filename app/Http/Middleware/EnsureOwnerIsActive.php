<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Заблокированный владелец не работает в кабинете, даже если токен ещё живой.
 */
class EnsureOwnerIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $owner = $request->user('owner');

        if (!$owner) {
            return response()->json([
                'success' => false,
                'code'    => 401,
                'message' => 'Unauthorized',
            ], 401);
        }

        if ($owner->isBlocked()) {
            return response()->json([
                'success' => false,
                'code'    => 403,
                'message' => 'Аккаунт заблокирован. Обратитесь в поддержку.',
                'errors'  => ['reason' => $owner->blocked_reason],
            ], 403);
        }

        return $next($request);
    }
}
