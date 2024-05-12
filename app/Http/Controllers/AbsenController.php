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
        // Mendapatkan semua anggota Senat berdasarkan id komisi
        $senat = ($id_komisi == '4') ? Senat::all() : Senat::where('id_komisi', $id_komisi)->get();

        // Mendapatkan informasi rapat berdasarkan kode unik
        $rapat = Rapat::where('kode_unik', $kode_unik)->first();

        // Membuat array untuk menyimpan id Senat yang sudah absen
        $senatYangSudahAbsen = [];

        // Jika informasi rapat ditemukan
        if ($rapat) {
            // Mendapatkan semua kehadiran pada rapat tersebut
            $kehadiran = Kehadiran::where('id_rapat', $rapat->id)->get();

            // Mengisi array $senatYangSudahAbsen dengan id Senat yang sudah absen pada rapat tersebut
            foreach ($kehadiran as $absen) {
                $senatYangSudahAbsen[] = $absen->id_senat;
            }
        }

        // Menyiapkan array Senat yang belum absen pada rapat tersebut
        $senatBelumAbsen = [];

        // Memeriksa setiap anggota Senat apakah sudah absen atau belum
        foreach ($senat as $anggota) {
            if (!in_array($anggota->id, $senatYangSudahAbsen)) {
                // Jika belum absen, tambahkan ke array Senat yang belum absen
                $senatBelumAbsen[] = $anggota;
            }
        }

        // Lakukan sesuatu dengan kode unik yang diterima
        return view('content.absen.absen', ['rapat' => $rapat, 'senats' => $senatBelumAbsen, 'id_komisi' => $id_komisi]);
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
            if (Kehadiran::where(['id_senat' => $request->id_senat, 'id_rapat' => $request->id_rapat])->exists()) {
                return redirect()->back()->with('error', 'Kehadiran Sudah Dicatat Sebelumnya');
            }

            // Jika cocok, buat kehadiran
            Kehadiran::create([
                'id_rapat' => $rapat->id,
                'id_senat' => $request->id_senat,
                'waktu' => now(),
                'verifikasi' => 'Absen'
            ]);
            return redirect()->back()->with('success', 'Berhasil Absen');
        } else {
            // Jika kata sandi tidak cocok, kembalikan respon dengan kesalahan
            return redirect()->back()->with('error', 'Password tidak cocok');
        }
    }

    public function verif_selected(Request $request, $id_rapat)
    {
        $status = $request->input('status');

        if ($request->input('selected_senats') == null) {
            return redirect()->back()->with('error', 'Tidak Ada Data Yang di pilih');
        } else {
            if ($status == 'Hadir' || $status == 'Tidak Hadir') {
                $selectedSenats = explode(',', $request->input('selected_senats'));
                Kehadiran::whereIn('id_senat', $selectedSenats)
                    ->where('id_rapat', $id_rapat)
                    ->update(['verifikasi' => $status]);

                return redirect()->back()->with('success', 'Verifikasi Berhasil');
            }
        }


        // Handle jika status tidak valid

        return redirect()->back()->with('error', 'Status tidak valid');
    }
}
