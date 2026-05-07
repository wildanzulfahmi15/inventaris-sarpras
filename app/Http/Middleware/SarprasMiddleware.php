<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SarprasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek login & role
        if (Auth::check() && Auth::user()->role === 'sarpras') {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Anda harus login sebagai Sarpras.');
    }
}
