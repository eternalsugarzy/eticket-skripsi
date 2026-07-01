<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengunjungAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('pengunjung')->check()) {
            return redirect()->route('pengunjung.login')
                ->with('error', 'Silakan login untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}