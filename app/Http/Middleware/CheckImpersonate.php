<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckImpersonate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('impersonator_id')) {
            // Add impersonator info to all views
            view()->share('isImpersonating', true);
            view()->share('impersonator', \App\Models\User::find(session('impersonator_id')));
        } else {
            view()->share('isImpersonating', false);
        }

        return $next($request);
    }
}
