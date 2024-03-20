<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function absen($kode_unik)
    {
        $rapat = Rapat::where('kode_unik',$kode_unik)->first();
        // Lakukan sesuatu dengan kode unik yang diterima
        return view('content.absen.absen', ['rapat' => $rapat]);
    }

    public function kehadiran(Request $request){
        $senat = User::where('username',$request->nip)->first();
        
    }
}
