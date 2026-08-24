<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackDailyStreak
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $user = $request->user();
            $today = now()->startOfDay();
            $lastActive = $user->last_active_at ? \Carbon\Carbon::parse($user->last_active_at)->startOfDay() : null;

            if (!$lastActive || $lastActive->lt($today)) {
                if ($lastActive && $lastActive->isYesterday()) {
                    $user->streak_count++;
                } else {
                    $user->streak_count = 1;
                }
                $user->last_active_at = now();
                $user->save();
            }
        }

        return $next($request);
    }
}
