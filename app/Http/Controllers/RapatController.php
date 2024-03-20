<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Komisi;
use Carbon\Carbon;
use App\Models\Rapat;
use Illuminate\Http\Request;
use LaravelQRCode\Facades\QRCode;

class RapatController extends Controller
{
    public function table()
    {
        $rapats = Rapat::all();
        return view('content.rapat.table-rapat', ['rapats' => $rapats]);
    }

    public function form()
    {
        $komisi = Komisi::all();
        return view('content.rapat.form-rapat',['komisis' => $komisi]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
        ]);
        $kode_unik = uniqid();
        $url = route('absen', ['kode_unik'=>$kode_unik, 'id_komisi'=>$request->id_komisi]);

        // Nama file untuk menyimpan QR code
        $filename = $request->id_komisi . '-' . $kode_unik . '.png';
        $filename = str_replace(' ', '-', $filename);
        $filepath = 'QRCode/' . $filename;
        // Membuat QR code
        QRCode::url($url . '?code=' . $kode_unik .'&&komisi='. $request->id_komisi)
            ->setSize(8)
            ->setMargin(2)
            ->setOutfile(storage_path('app/public/' . $filepath))
            ->png();

        $waktu = Carbon::createFromFormat('Y-m-d H:i', $request->tanggal . ' ' . $request->jam);
        $expirationTime = $waktu->addDay(); // Menambah satu hari

        $rapat = Rapat::create([
            'id_komisi' => $request->id_komisi,
            'kode_unik' => $kode_unik,
            'tanggal' =>$request->tanggal,
            'jam' => $request->jam,
            'qr_code' =>  $filepath, 
            'status' => 'prepare',
            'time_expired' => $expirationTime,
        ]);
        return redirect()->route('list.rapat')->with('success', 'User berhasil ditambahkan.');
    }

    public function kehadiran($id){
        $kehadiran = Kehadiran::where('id_rapat',$id)->get();
        return view('content.rapat.kehadiran',['kehadirans' => $kehadiran]);
    }
}
