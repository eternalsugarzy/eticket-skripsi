<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kabupaten;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    const ROLE_VALID = 'in:admin,kasir,petugas,kadis_provinsi,kadis_kabkota';
    const ROLE_UNTUK_KADIS_KABKOTA = ['kasir', 'petugas'];

    // 1. Tampilkan Daftar
    public function index()
    {
        $user = auth()->user();
        $query = User::latest();

        // 🔒 SCOPING: kadis_kabkota hanya lihat akun wilayahnya sendiri (termasuk akunnya sendiri)
        if ($user->role === 'kadis_kabkota') {
            $query->where('id_kabupaten', $user->id_kabupaten);
        }

        $users = $query->get();
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
        $actor = auth()->user();

        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|' . self::ROLE_VALID,
            'nip' => 'nullable|string|max:30',
            'id_kabupaten' => 'nullable|exists:kabupatens,id',
        ]);

        // 🔒 SCOPING: kadis_kabkota hanya boleh menambah akun Kasir/Petugas untuk wilayahnya sendiri
        if ($actor->role === 'kadis_kabkota' && !in_array($request->role, self::ROLE_UNTUK_KADIS_KABKOTA)) {
            abort(403, 'Anda hanya bisa menambahkan akun Kasir atau Petugas.');
        }

        $idKabupaten = $actor->role === 'kadis_kabkota'
            ? $actor->id_kabupaten
            : ($request->role === 'kadis_kabkota' ? $request->id_kabupaten : null);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nip' => $request->nip,
            'id_kabupaten' => $idKabupaten,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // 4. Form Edit (Menampilkan data lama)
    public function edit(User $user)
    {
        $this->cekAksesUser($user);

        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();
        return view('users.edit', compact('user', 'kabupatens'));
    }

    // 5. Proses Update (Simpan Perubahan)
    public function update(Request $request, User $user)
    {
        $this->cekAksesUser($user);
        $actor = auth()->user();

        $request->validate([
            'nama' => 'required',
            // Username harus unik, tapi KECUALI milik user ini sendiri
            'username' => 'required|unique:users,username,'.$user->id,
            'role' => 'required|' . self::ROLE_VALID,
            'nip' => 'nullable|string|max:30',
            'id_kabupaten' => 'nullable|exists:kabupatens,id',
        ]);

        if ($actor->role === 'kadis_kabkota') {
            if ($user->id === $actor->id) {
                // Tidak boleh ubah role/wilayah akun sendiri (cegah eskalasi hak akses)
                $role = $user->role;
                $idKabupaten = $user->id_kabupaten;
            } else {
                if (!in_array($request->role, self::ROLE_UNTUK_KADIS_KABKOTA)) {
                    abort(403, 'Anda hanya bisa mengatur akun Kasir atau Petugas.');
                }
                $role = $request->role;
                $idKabupaten = $actor->id_kabupaten;
            }
        } else {
            $role = $request->role;
            $idKabupaten = $role === 'kadis_kabkota' ? $request->id_kabupaten : null;
        }

        // Siapkan data yang mau diupdate
        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $role,
            'nip' => $request->nip,
            'id_kabupaten' => $idKabupaten,
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
        $this->cekAksesUser($user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    // =========================================================
    // PRIVATE HELPER — admin/kadis_provinsi akses semua akun;
    // kadis_kabkota hanya akun di wilayahnya sendiri (termasuk akunnya sendiri)
    // =========================================================
    private function cekAksesUser(User $user)
    {
        $actor = auth()->user();
        if (in_array($actor->role, ['admin', 'kadis_provinsi'])) {
            return;
        }

        if ($actor->role === 'kadis_kabkota' && $user->id_kabupaten == $actor->id_kabupaten) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke akun user ini.');
    }
}