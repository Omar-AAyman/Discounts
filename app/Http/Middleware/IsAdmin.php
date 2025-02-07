<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{

    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has the `is_admin` attribute set to true
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Redirect non-admin users or guests to a forbidden or login page
        return redirect()->route('home')->with('error', 'Access denied.');
    }
}
