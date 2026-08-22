<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Middleware untuk mengatur locale aplikasi berdasarkan session.
     * Default ke 'id' (Bahasa Indonesia) jika belum dipilih.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', 'id');

        if (in_array($locale, ['id', 'en'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}

