<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengunjung;

class PengunjungAuthController extends Controller
{
    // Form Login Pengunjung
    public function showLoginForm()
    {
        if (Auth::guard('pengunjung')->check()) {
            return redirect()->route('pengunjung.riwayat');
        }
        return view('frontend.auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('pengunjung')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('pengunjung.riwayat'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Form Register Pengunjung
    public function showRegisterForm()
    {
        if (Auth::guard('pengunjung')->check()) {
            return redirect()->route('pengunjung.riwayat');
        }
        return view('frontend.auth.register');
    }

    // Proses Register
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:pengunjungs,email'],
            'no_wa'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $pengunjung = Pengunjung::create([
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'no_wa'    => $data['no_wa'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        Auth::guard('pengunjung')->login($pengunjung);

        return redirect()->route('pengunjung.riwayat')
            ->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('pengunjung')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    // Riwayat Pesanan milik pengunjung yang login
    public function riwayat()
    {
        $pengunjung = Auth::guard('pengunjung')->user();
        $pesanans = $pengunjung->pesanans()->with('objekWisata')->latest()->get();

        return view('frontend.pengunjung.riwayat', compact('pesanans'));
    }
}