<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next, $roles = [])
    {
        if (!Auth::check())
            return redirect()->route('login');

        if ($roles) {
            $allowedRoles = explode('-', $roles);
            $userRole = Auth::user()->role;

            if (!in_array($userRole, $allowedRoles))
                abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
