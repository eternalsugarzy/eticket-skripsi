<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Tampilkan Daftar
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    // 2. Form Tambah
    public function create()
    {
        return view('users.create');
    }

    // 3. Simpan Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // 4. Form Edit (Menampilkan data lama)
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // 5. Proses Update (Simpan Perubahan)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required',
            // Username harus unik, tapi KECUALI milik user ini sendiri
            'username' => 'required|unique:users,username,'.$user->id, 
            'role' => 'required'
        ]);

        // Siapkan data yang mau diupdate
        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
        ];

        // Cek apakah password diisi? Kalau ya, hash ulang. Kalau kosong, abaikan.
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    // 6. Proses Hapus
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}