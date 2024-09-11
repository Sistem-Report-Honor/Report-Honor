<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Komisi;
use Carbon\Carbon;
use App\Models\Rapat;
use Illuminate\Http\Request;
use LaravelQRCode\Facades\QRCode;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;


class RapatController extends Controller
{
    public function table()
    {
        $rapats = Rapat::all();
        foreach ($rapats as $rapat) {
            if ($rapat->status == 'mulai') {
                if (now() >= $rapat->time_expired) {
                    $rapat->status = 'selesai';
                    $rapat->save();
                }
            }
        }
        return view('content.rapat.table-rapat', ['rapats' => $rapats]);
    }

    public function form()
    {
        $komisi = Komisi::all();
        return view('content.rapat.form-rapat', ['komisis' => $komisi]);
    }

        public function create(Request $request)
        {
            $request->validate([
                'tanggal' => 'required|date',
                'jam' => 'required',
            ]);
            $kode_unik = uniqid();
            $url = route('absen', ['kode_unik' => $kode_unik, 'id_komisi' => $request->id_komisi]);

            // Nama file untuk menyimpan QR code
            $filename = $request->id_komisi . '-' . $kode_unik . '.png';
            $filename = str_replace(' ', '-', $filename);
            $filepath = 'QRCode/' . $filename;
            // Membuat QR code
            QRCode::url($url . '?code=' . $kode_unik . '&komisi=' . $request->id_komisi)
                ->setSize(8)
                ->setMargin(2)
                ->setOutfile(storage_path('app/public/' . $filepath))
                ->png();

            $waktu = Carbon::createFromFormat('Y-m-d H:i', $request->tanggal . ' ' . $request->jam);
            $expirationTime = $waktu->addDay(); // Menambah satu hari


            $rapat = Rapat::create([
                'id_komisi' => $request->id_komisi,
                'kode_unik' => $kode_unik,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'qr_code' =>  $filepath,
                'status' => 'prepare',
                'time_expired' => $expirationTime,
            ]);
            return redirect()->back()->with('success', 'Rapat berhasil Dibuat.');
        }

    public function kehadiran($id)
    {
        $kehadirans = Kehadiran::where('id_rapat', $id)->get();
        $rapat = Kehadiran::where('id_rapat', $id)->first();
        return view('content.rapat.kehadiran', ['kehadirans' => $kehadirans, 'rapat' => $rapat]);
    }

    public function statusMulai($id)
    {
        $rapat = Rapat::findOrFail($id);
        $rapat->status = 'mulai';
        $rapat->save();
        return redirect()->back()->with('success', 'rapat berhasil Di Mulai');
    }

    public function statusSelesai($id)
    {

        $rapat = Rapat::findOrFail($id);
        $rapat->status = 'selesai';
        $rapat->save();
        $kehadirans = Kehadiran::where('id_rapat', $id)->where('verifikasi', 'absen')->get();
        if ($kehadirans != null) {
            foreach ($kehadirans as $absen) {
                $absen->verifikasi = 'Tidak Hadir';
                $absen->save();
            }
        }

        return redirect()->back()->with('success', 'rapat berhasil Di akhiri');
    }


    public function printQR($id)
{
    $rapat = Rapat::findOrFail($id);
    return view('content.rapat.print-qrcode', compact('rapat'));
}


public function generatePDF($id)
{
    $rapat = Rapat::findOrFail($id);
    $dompdf = new Dompdf();

    $html = "
    <div style='text-align: center; margin-bottom: 300px;'>
        <h1 style='font-size: 50px; margin-bottom: 20px;'>{$rapat->komisi->komisi}</h1>
        <p style='font-size: 15px;'>Tanggal: {$rapat->tanggal}</p>
        <p>Jam: {$rapat->jam}</p>
    </div>
    ";

    $dompdf->loadHtml($html);
    $dompdf->render();
    
    $canvas = $dompdf->getCanvas();
    $qrCodePath = storage_path("app/public/{$rapat->qr_code}");
    $imageSize = getimagesize($qrCodePath);
    $imageWidth = $imageSize[0] * 1.5;
    $imageHeight = $imageSize[1] * 1.5;

    // Adjust the position where you want the QR code to be placed
    $qrCodeX = 100;
    $qrCodeY = 200;

    $canvas->image($qrCodePath, $qrCodeX, $qrCodeY, $imageWidth, $imageHeight);

    // Add the text below the QR code
    $textX = $qrCodeX + ($imageWidth / 2); // Center the text horizontally below the image
    $textY = $qrCodeY + $imageHeight + 20; // Position the text 20 points below the image
    $font = $dompdf->getFontMetrics()->get_font('helvetica', 'normal');
    $fontSize = 12;

    $text = "senat.polmed.ac.id";
    $textWidth = $dompdf->getFontMetrics()->getTextWidth($text, $font, $fontSize);

    // Adjust the x-coordinate to center the text
    $textX -= $textWidth / 2;

    $canvas->text($textX, $textY, $text, $font, $fontSize);

    $fileName = "{$rapat->komisi->komisi}_{$rapat->tanggal}.pdf";

    return $dompdf->stream($fileName);
}




 public function delete($id)
{
    $rapat = Rapat::findOrFail($id);

    // Hapus file QR Code
    Storage::delete('public/' . $rapat->qr_code);

    // Hapus rapat
    $rapat->delete();

    return redirect()->back()->with('success', 'Rapat berhasil dihapus');
}

public function showKehadiran($id)
{
    $rapat = Rapat::find($id);
    $kehadirans = Kehadiran::where('rapat_id', $id)->get();

    if (!$rapat) {
        return redirect()->route('rapat.index')->with('error', 'Rapat tidak ditemukan');
    }

    return view('content.rapat.kehadiran', compact('rapat', 'kehadirans'));
}

public function printKehadiran($id)
{
    $kehadirans = Kehadiran::where('id_rapat', $id)->get();
    $rapat = Rapat::findOrFail($id);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menambahkan judul
    $sheet->setCellValue('A1', 'Daftar Kehadiran Rapat');
    $sheet->setCellValue('A2', 'Komisi: ' . $rapat->komisi->komisi);
    $sheet->setCellValue('A3', 'Tanggal: ' . $rapat->tanggal);
    $sheet->setCellValue('A4', 'Jam: ' . $rapat->jam);

    // Menambahkan header tabel
    $sheet->setCellValue('A6', 'Nama');
    $sheet->setCellValue('B6', 'Status Kehadiran');

    // Menambahkan data kehadiran
    $row = 7;
    foreach ($kehadirans as $kehadiran) {
        $sheet->setCellValue('A' . $row, $kehadiran->nama);
        $sheet->setCellValue('B' . $row, $kehadiran->verifikasi == 'absen' ? 'Hadir' : 'Tidak Hadir');
        $row++;
    }

    // Menyimpan file Excel ke storage sementara
    $fileName = 'Kehadiran_Rapat_' . $rapat->komisi->komisi . '_' . $rapat->tanggal . '.xlsx';
    $filePath = storage_path('app/public/' . $fileName);

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    // Mengembalikan file sebagai response download dan menghapus file setelah diunduh
    return response()->download($filePath)->deleteFileAfterSend(true);
}


}

