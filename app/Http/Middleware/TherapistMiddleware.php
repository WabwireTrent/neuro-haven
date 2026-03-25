<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TherapistMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->role === 'therapist' || auth()->user()->role === 'admin')) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    }
}
