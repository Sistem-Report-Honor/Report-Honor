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
            if (Kehadiran::where(['id_senat'=> $request->id_senat ,'id_rapat'=>$request->kode_unik])->exists()) {
                return response()->json(['error' => 'Kehadiran sudah dicatat sebelumnya'], 400);
            }

            // Jika cocok, buat kehadiran
            Kehadiran::create([
                'id_rapat' => $rapat->id,
                'id_senat' => $request->id_senat,
                'waktu' => now(),
                'verifikasi' => 'Absen'
            ]);
            return response()->json(['message' => 'Kehadiran berhasil dicatat'], 200);
        } else {
            // Jika kata sandi tidak cocok, kembalikan respon dengan kesalahan
            return response()->json(['error' => 'Kata sandi salah'], 401);
        }
    }

    public function verif(Request $request, $id_rapat, $id_senat){
        if($request->status == 'Hadir'){
            Kehadiran::where(['id_rapat'=>$id_rapat, 'id_senat'=>$id_senat])->update([
                'verifikasi'=>$request->status]);

            return redirect()->back()->with('succes','Verifikasi Berhasil');
        }else if($request->status == 'Tidak Hadir'){
            Kehadiran::where(['id_rapat'=>$id_rapat, 'id_senat'=>$id_senat])->update([
                'verifikasi'=>$request->status]);

            return redirect()->back()->with('succes','Verifikasi Berhasil');
        }
    }

    public function verif_selected(Request $request, $id_rapat) {
        $status = $request->input('status');
    
        if ($status == 'Hadir' || $status == 'Tidak Hadir') {
            $selectedSenats = explode(',', $request->input('selected_senats'));
    
            Kehadiran::whereIn('id_senat', $selectedSenats)
                ->where('id_rapat', $id_rapat)
                ->update(['verifikasi' => $status]);
    
            return redirect()->back()->with('success', 'Verifikasi Berhasil');
        }
    
        // Handle jika status tidak valid
    
        return redirect()->back()->with('error', 'Status tidak valid');
    }
    
}
