<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kabupaten;
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
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        return view('users.create', compact('kabupatens'));
    }

    // 3. Simpan Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'nip' => 'nullable|string|max:30',
            'id_kabupaten' => 'nullable|exists:kabupatens,id',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nip' => $request->nip,
            'id_kabupaten' => $request->role === 'kadis_kabkota' ? $request->id_kabupaten : null,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // 4. Form Edit (Menampilkan data lama)
    public function edit(User $user)
    {
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        return view('users.edit', compact('user', 'kabupatens'));
    }

    // 5. Proses Update (Simpan Perubahan)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required',
            // Username harus unik, tapi KECUALI milik user ini sendiri
            'username' => 'required|unique:users,username,'.$user->id,
            'role' => 'required',
            'nip' => 'nullable|string|max:30',
            'id_kabupaten' => 'nullable|exists:kabupatens,id',
        ]);

        // Siapkan data yang mau diupdate
        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
            'nip' => $request->nip,
            'id_kabupaten' => $request->role === 'kadis_kabkota' ? $request->id_kabupaten : null,
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