<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Get the authenticated user and verify role
        $user = Auth::user();
        
        if ($user && $user->jabatan === $role) {
            return $next($request);
        }

        // Unauthorized access if the role does not match
        abort(403, 'Unauthorized access.');
    }
}

