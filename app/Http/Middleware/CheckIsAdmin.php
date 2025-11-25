<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in AND is an admin
        if (auth()->check() && auth()->user()->is_admin) {
            // If they are an admin, let them continue
            return $next($request);
        }

        // If not an admin, send them back to their dashboard
        return redirect('/dashboard');
    }
}