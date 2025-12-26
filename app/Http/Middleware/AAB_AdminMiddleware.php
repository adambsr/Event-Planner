<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AAB_AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user has admin role using Spatie
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé. Cette page est réservée aux administrateurs.');
        }

        return $next($request);
    }
}
