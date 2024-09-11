<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rapat;
use App\Models\Senat;
use App\Models\Kehadiran;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AbsenController extends Controller
{
    public function absen($kode_unik, $id_komisi)
    {

        $rapat = Rapat::where('kode_unik', $kode_unik)->first();
        if (!$rapat) {
            return redirect()->back()->with('error', 'Rapat tidak ditemukan.');
        }
        if ($id_komisi == '4') {
            $senat = Senat::all();
        } else {
            $senat = Senat::where('id_komisi', $id_komisi)
                          ->orWhere('id_komisi', '5')
                          ->get();
        }
        $kehadiran = Kehadiran::where('id_rapat', $rapat->id)->pluck('id_senat')->toArray();

        return view('content.absen.absen', [
            'rapat' => $rapat,
            'senats' => $senat,
            'id_komisi' => $id_komisi,
            'kehadiran' => $kehadiran
        ]);
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

        // Jika cocok, buat kehadiran
        Kehadiran::create([
                'id_rapat' => $rapat->id,
                'id_senat' => $request->id_senat,
                'waktu' => now(),
                'verifikasi' => 'Absen'
        ]);
        
            return redirect()->back()->with('success','Berhasil Absen');
        } else {
            // Jika kata sandi tidak cocok, kembalikan respon dengan kesalahan
            return redirect()->back()->with('error','Password tidak cocok');
        }
    }

    public function verif_selected(Request $request, $id_rapat) {
        $status = $request->input('status');
    
        if($request->input('selected_senats') == null){
            return redirect()->back()->with('error','Tidak Ada Data Yang di pilih');
        }else{
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
    
  public function exportKehadiran($id_rapat)
{
    // Fetch attendance data
    $rapat = Rapat::find($id_rapat);
    $kehadiran = Kehadiran::where('id_rapat', $id_rapat)->get();
    
    // Create a new Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $headers = ['No', 'Nama', 'Waktu', 'Verifikasi'];
    $sheet->fromArray($headers, null, 'A1');

    // Make headers bold
    $sheet->getStyle('A1:D1')->getFont()->setBold(true);

    // Add data
    $row = 2;
    $counter = 1; // Initialize a counter for the serial number
    foreach ($kehadiran as $item) {
        $user = User::where('id_senat', $item->id_senat)->first();
        $sheet->setCellValue('A' . $row, $counter); // Set sequential number
        $sheet->setCellValue('B' . $row, $user->name ?? 'Unknown');
        $sheet->setCellValue('C' . $row, $item->waktu);
        $sheet->setCellValue('D' . $row, $item->verifikasi);
        $row++;
        $counter++; // Increment the counter
    }

    // Add borders
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ];
    $sheet->getStyle('A1:D' . ($row - 1))->applyFromArray($styleArray);

    // Set column auto-width
    foreach (range('A', 'D') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Write to a file
    $writer = new Xlsx($spreadsheet);
    $fileName = 'Kehadiran_' . $rapat->komisi->komisi .$rapat->tanggal. '.xlsx';
    $filePath = public_path($fileName);

    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}

    
}
