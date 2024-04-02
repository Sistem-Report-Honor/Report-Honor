<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use App\Models\Senat;
use App\Models\Kehadiran;
use Illuminate\Http\Request;

class CobaController extends Controller
{
    public function reportDetail()
    {
        $rapats = Rapat::all();
        $senats = Senat::with(['user', 'golongan'])->get();
        $kehadiransenat = [];

        foreach ($senats as $senat) {
            foreach ($rapats as $rapat) {
                $kehadiran = Kehadiran::where('id_senat', $senat->id)
                    ->where('id_rapat', $rapat->id)
                    ->get();

                // Menyimpan kehadiran hanya jika tidak kosong
                if ($kehadiran->isNotEmpty()) {
                    $kehadiransenat[$senat->id][$rapat->id] = $kehadiran;
                }
            }
        }

        $honorSenat = [];
        $rapatPresent = [];

        foreach ($kehadiransenat as $senatId => $rapatKehadiran) {
            foreach ($rapatKehadiran as $rapatId => $kehadiran) {
                foreach ($kehadiran as $hadir) {
                    dd($hadir);
                    $senat = Senat::find($senatId);
                    $rapat = Rapat::find($rapatId);

                    // Menyimpan nilai honor ke dalam array honorSenat
                    $honorSenat[$senat->id] = $senat;

                    // Menyimpan nama rapat ke dalam array rapatPresent
                    $rapatPresent[$rapat->id] = $rapat->komisi->komisi;
                }
            }
        }

        return view('content.coba', [
            'rapats' => $rapatPresent,
            'honors' => $honorSenat,
            'senats' => $senats,
        ]);
    }
}
