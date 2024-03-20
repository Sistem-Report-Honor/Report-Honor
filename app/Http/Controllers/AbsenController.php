<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rapat;
use App\Models\Senat;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AbsenController extends Controller
{
    public function absen($kode_unik, $id_komisi)
    {
        $senat = '';
        if ($id_komisi == '4') {
            $senat = Senat::all();
        } else {
            $senat = Senat::where('id_komisi', $id_komisi)->get();
        }
        $rapat = Rapat::where('kode_unik', $kode_unik)->first();
        // Lakukan sesuatu dengan kode unik yang diterima
        return view('content.absen.absen', ['rapat' => $rapat, 'senats' => $senat]);
    }

    public function kehadiran(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_senat' => 'required|exists:users,id_senat', // Pastikan id_senat ada dalam tabel users
            'password' => 'required|string',
        ]);

        // Cari rapat berdasarkan kode_unik
        $rapat = Rapat::where('kode_unik', $request->kode_unik)->firstOrFail();

        // Cari user berdasarkan id_senat
        $user = User::where('id_senat', $request->id_senat)->firstOrFail();

        // Periksa apakah kata sandi yang diberikan sama dengan kata sandi pengguna
        if (Hash::check($request->password, $user->password)) {
            // Periksa apakah id_senat sudah ada dalam tabel Kehadiran
            if (Kehadiran::where('id_senat', $request->id_senat)->exists()) {
                return response()->json(['error' => 'Kehadiran sudah dicatat sebelumnya'], 400);
            }

            // Jika cocok, buat kehadiran
            Kehadiran::create([
                'id_rapat' => $rapat->id,
                'id_senat' => $request->id_senat,
                'waktu' => now(),
                'verifikasi' => ''
            ]);
            return response()->json(['message' => 'Kehadiran berhasil dicatat'], 200);
        } else {
            // Jika kata sandi tidak cocok, kembalikan respon dengan kesalahan
            return response()->json(['error' => 'Kata sandi salah'], 401);
        }
    }
}
