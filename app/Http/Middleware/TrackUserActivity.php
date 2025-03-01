<?php

namespace App\Http\Middleware;

use App\Models\UserActivity;
use App\Models\UserSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()) {
            $user = $request->user();
            $sessionToken = $request->header('Session-Token');

            if ($sessionToken) {
                $session = UserActivity::where('user_id', $user->id)
                    ->where('session_token', $sessionToken)
                    ->first();

                if ($session) {
                    // Update last activity time
                    $session->update(['last_activity' => now()]);
                } else {
                    // Fetch last session before creating a new one
                    $lastSession = UserActivity::where('user_id', $user->id)
                        ->latest('last_activity')
                        ->first();

                    // Create new session
                    UserActivity::create([
                        'user_id' => $user->id,
                        'session_token' => $sessionToken,
                        'last_activity' => now(),
                    ]);

                    // Attach last session time to the response
                    $request->merge(['last_session' => $lastSession?->last_activity]);
                }
            }
        }
        return $next($request);
    }
}
