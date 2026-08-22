<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Ganti bahasa aktif (locale) dan simpan ke session.
     * Hanya menerima 'id' (Indonesia) dan 'en' (English).
     */
    public function switch($locale)
    {
        if (! in_array($locale, ['id', 'en'])) {
            abort(400, 'Locale not supported.');
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        return redirect()->back();
    }
}

