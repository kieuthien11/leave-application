<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (auth()->check() && auth()->user()->role !== $role) {
            // Redirect or return an error response if not admin
            return redirect('/'); // or show a 403 forbidden page
        }

        return $next($request);
    }
}
