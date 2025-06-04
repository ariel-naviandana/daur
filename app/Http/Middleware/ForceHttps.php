<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        // Force HTTPS scheme for URL generation
        URL::forceScheme('https');

        // Skip redirection if already secure or in production on Vercel
        if ($request->secure() || env('APP_ENV') === 'production') {
            return $next($request);
        }

        // Redirect to HTTPS only if not secure (for non-Vercel environments)
        return redirect()->secure($request->getRequestUri());
    }
}
