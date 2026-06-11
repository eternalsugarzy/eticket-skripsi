<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Menampilkan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Cek Login (Username & Password)
    public function login(Request $request)
    {
        // Validasi: Pastikan username & password diisi
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Cek ke Database (Laravel otomatis menghash password inputan dan membandingkan)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Jika sukses, lempar ke halaman Dashboard
            return redirect()->intended('/');
        }

        // Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Kembalikan ke halaman login
        return redirect('/login');
    }
}