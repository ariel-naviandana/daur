<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!Auth::check())
            return redirect()->route('login')->with('error', 'Please login to access this page.');

        if ($role && Auth::user()->role !== $role)
            abort(403, 'You do not have permission to access this page.');

        return $next($request);
    }
}
