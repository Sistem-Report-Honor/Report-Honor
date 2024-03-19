<?php

namespace App\Http\Controllers;

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
        return view('content.rapat.form-rapat');
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'tanggal' => 'required|date',
            'jam' => 'required',
        ]);
        $kode_unik = uniqid();
        $url = route('absen', [$kode_unik]);

        // Nama file untuk menyimpan QR code
        $filename = $request->nama . '-' . $kode_unik . '.png';
        $filename = str_replace(' ', '-', $filename);
        $filepath = 'QRCode/' . $filename;
        // Membuat QR code
        QRCode::url($url . '?code=' . $kode_unik)
            ->setSize(8)
            ->setMargin(2)
            ->setOutfile(storage_path('app/public/' . $filepath))
            ->png();

        $waktu = Carbon::createFromFormat('Y-m-d H:i', $request->tanggal . ' ' . $request->jam);
        $expirationTime = $waktu->addDay(); // Menambah satu hari

        $rapat = Rapat::create([
            'nama' => $request->nama,
            'kode_unik' => $kode_unik,
            'tanggal' =>$request->tanggal,
            'jam' => $request->jam,
            'qr_code' =>  $filepath, 
            'status' => 'mulai',
            'time_expired' => $expirationTime,
        ]);
        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }
}
