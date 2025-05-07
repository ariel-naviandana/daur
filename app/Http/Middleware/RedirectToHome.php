<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectToHome
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $redirectTo = $user->role === 'admin' ? '/admin' : '/';
            return redirect($redirectTo);
        }

        return $next($request);
    }
}
