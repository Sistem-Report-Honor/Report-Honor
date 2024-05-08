<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Komisi;
use App\Models\Kehadiran;
use App\Models\User;
use App\Models\Senat;
use App\Models\Rapat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\Permission\Models\Role;

class ReportController extends Controller
{
      
private function calculateHonorariums($senats)
{
    // Mengambil data kehadiran dengan verifikasi 'Hadir'
    $kehadirans = Kehadiran::where('verifikasi', 'Hadir')->get();

    // Menghitung jumlah kehadiran untuk setiap anggota rapat
    $jumlahKehadiran = [];
    foreach ($senats as $senat) {
        // Menghitung jumlah kehadiran berdasarkan id senat
        $jumlahKehadiran[$senat->id] = $kehadirans->where('id_senat', $senat->id)->count();
    }

    // Menghitung honorarium untuk setiap anggota senat
    $honorariumsPerSenat = [];
    foreach ($senats as $senat) {
        $golongan = $senat->golongan;
        if ($golongan) {
            $honorarium = $golongan->honor;
            $pph = $golongan->pph;
            $honorariumsPerSenat[$senat->id] = ($honorarium - $pph) * ($jumlahKehadiran[$senat->id] ?? 0);
        } else {
            // Atur nilai honorarium menjadi 0 jika golongan tidak ditemukan
            $honorariumsPerSenat[$senat->id] = 0;
        }
    }

    return $honorariumsPerSenat;
}

public function reportDasar()
{
    // Mendapatkan data Senat
    $senats = Senat::with(['user', 'golongan'])->get();

    // Menghitung honorariums
    $honorariumsPerSenat = $this->calculateHonorariums($senats);

    return view('content.honor.honor-dasar', ['senats' => $senats, 'honorariums' => $honorariumsPerSenat]);
}

public function printReport()
{
    // Buat instance Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'No.');
    $sheet->setCellValue('B1', 'Nomor Rekening');
    $sheet->setCellValue('C1', 'Nama Rekening');
    $sheet->setCellValue('D1', 'Honorarium');

    // Mendapatkan data Senat
    $senats = Senat::with(['user', 'golongan'])->get();

    // Menghitung honorariums
    $honorariumsPerSenat = $this->calculateHonorariums($senats);

    // Mendefinisikan style untuk header
    $headerStyle = [
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
    ];

    // Terapkan style pada header
    $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

    // Mendefinisikan lebar kolom dan style border untuk setiap kolom
    $columnWidths = [10, 20, 30, 15]; // Lebar kolom dalam satuan karakter
    foreach(range('A', 'D') as $columnKey => $column) {
        $sheet->getColumnDimension($column)->setWidth($columnWidths[$columnKey]);
        $sheet->getStyle($column . '1')->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]]);
    }

    // Mengisi data ke dalam baris-baris selanjutnya
    $row = 2;
    foreach ($senats as $index => $senat) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $senat->no_rek);
        $sheet->setCellValue('C' . $row, $senat->nama_rekening);
        $sheet->setCellValue('D' . $row, isset($honorariumsPerSenat[$senat->id]) ? $honorariumsPerSenat[$senat->id] : 'N/A');

        // Terapkan style border untuk setiap sel pada baris ini
        foreach(range('A', 'D') as $column) {
            $sheet->getStyle($column . $row)->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]]);
        }

        $row++;
    }

    // Buat file Excel
    $excel_file_path = public_path('storage/Reportdasar/report.xlsx'); // Sesuaikan dengan jalur yang tepat
    $writer = new Xlsx($spreadsheet);
    $writer->save($excel_file_path);

    // Berikan file Excel untuk diunduh
    return response()->download($excel_file_path, 'report.xlsx');
}


private function generateReport($senats, $rapats) {
    // Inisialisasi array untuk menyimpan kehadiran dan honorarium per rapat
    $kehadirans = [];
    $honorariumsPerRapat = [];
    $honorariumsPerSenat = [];
    $honors = [];
    $pphs = [];

    // Lakukan perulangan melalui semua senat
    foreach ($senats as $senat) {
        // Inisialisasi total honorarium untuk senat ini
        $totalHonorariumSenat = 0;
        $totalhonors = 0;
        $totalpphs = 0;

        // Inisialisasi array kehadiran untuk senat ini di setiap rapat
        $kehadirans[$senat->id] = [];

        // Lakukan perulangan melalui semua rapat
        foreach ($rapats as $rapat) {
            // Periksa apakah senat hadir dalam rapat yang sedang diproses
            $hadir = Kehadiran::where('id_senat', $senat->id)
                ->where('id_rapat', $rapat->id)
                ->where('verifikasi', 'Hadir')
                ->exists();

            // Simpan status kehadiran senat dalam rapat
            $kehadirans[$senat->id][$rapat->id] = $hadir;

            if ($hadir) {
                // Dapatkan informasi golongan senat yang hadir
                $golongan = $senat->golongan;

                // Hitung honorarium untuk senat ini pada rapat ini
                $honor = $golongan ? $golongan->honor : 0;
                $pph = $golongan ? $golongan->pph : 0;
                $honorarium = $honor - $pph;

                // Tambahkan honorarium senat ke total honorarium rapat
                $totalHonorariumSenat += $honorarium;
                $totalhonors += $honor;
                $totalpphs += $pph;

                // Simpan honorarium per rapat
                if (!isset($honorariumsPerRapat[$rapat->id])) {
                    $honorariumsPerRapat[$rapat->id] = [];
                }
                $honorariumsPerRapat[$rapat->id] = $honorarium;
            }
        }

        // Simpan total honorarium untuk senat ini dalam array
        $honorariumsPerSenat[$senat->id] = $totalHonorariumSenat;
        $honors[$senat->id] = $totalhonors;
        $pphs[$senat->id] = $totalpphs;
    }

    return [
        'kehadirans' => $kehadirans,
        'honorariumsPerRapat' => $honorariumsPerRapat,
        'honorariumsPerSenat' => $honorariumsPerSenat,
        'honors' => $honors,
        'pphs' => $pphs,
    ];
}

public function reportDetail() {
    // Dapatkan semua senat dengan relasi yang diperlukan
    $senats = Senat::with(['user', 'golongan', 'komisi'])->get();

    // Dapatkan semua rapat
    $rapats = Rapat::all();

    // Generate report
    $reportData = $this->generateReport($senats, $rapats);

    // Tampilkan data ke view
    return view('content.honor.honor-detail', array_merge($reportData, [
        'senats' => $senats,
        'rapats' => $rapats,
    ]));
}

public function reportPribadi() {
    // Dapatkan ID senat dari pengguna yang saat ini diautentikasi
    $id_senat = Auth::user()->id_senat;

    // Dapatkan senat dengan ID yang diberikan bersama dengan relasinya
    $senat = Senat::with(['user', 'golongan', 'komisi'])->findOrFail($id_senat);

    // Dapatkan semua rapat
    $rapats = Rapat::all();

    // Generate report
    $reportData = $this->generateReport([$senat], $rapats);

    // Tampilkan data ke view
    return view('content.honor.honor-pribadi', array_merge($reportData, [
        'senat' => $senat,
        'rapats' => $rapats,
    ]));
}

}
