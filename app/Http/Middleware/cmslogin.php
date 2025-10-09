<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class cmslogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // contoh: pakai guard default
        if (!Auth::check()) {
            return redirect('/login');
        }

        // kalau butuh role:
        if ($role && Auth::user()->role !== $role) {
            abort(403); // atau redirect('/login')
        }

        return $next($request);
    }
}
