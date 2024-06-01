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
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;


class ReportController extends Controller
{


    private function calculateHonorariums($senats, $month, $year)
    {
        // Mengambil data kehadiran dengan verifikasi 'Hadir' dan filter berdasarkan bulan dan tahun
        $kehadirans = Kehadiran::where('verifikasi', 'Hadir')
            ->whereMonth('waktu', $month)
            ->whereYear('waktu', $year)
            ->get();

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

    public function reportDasar(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month); // Default bulan ini
        $year = $request->input('year', Carbon::now()->year); // Default tahun ini

        // Mendapatkan data Senat
        $senats = Senat::with(['user', 'golongan'])->get();

        // Menghitung honorariums
        $honorariumsPerSenat = $this->calculateHonorariums($senats, $month, $year);

        return view('content.honor.honor-dasar', ['senats' => $senats, 'honorariums' => $honorariumsPerSenat, 'month' => $month, 'year' => $year]);
    }


    public function printReport(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month); // Default bulan ini
        $year = $request->input('year', Carbon::now()->year); // Default tahun ini

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
        $honorariumsPerSenat = $this->calculateHonorariums($senats, $month, $year);

        // Mendefinisikan style untuk header
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
        ];

        // Terapkan style pada header
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        // Mendefinisikan lebar kolom dan style border untuk setiap kolom
        $columnWidths = [10, 30, 30, 15]; // Lebar kolom dalam satuan karakter
        foreach (range('A', 'D') as $columnKey => $column) {
            $sheet->getColumnDimension($column)->setWidth($columnWidths[$columnKey]);
            $sheet->getStyle($column . '1')->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]]);
        }

        // Mengisi data ke dalam baris-baris selanjutnya
        $row = 2;
        foreach ($senats as $index => $senat) {
            $sheet->setCellValue('A' . $row, $index + 1);

            // Mengatur nomor rekening sebagai teks
            $sheet->setCellValueExplicit('B' . $row, $senat->no_rek, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $sheet->setCellValue('C' . $row, $senat->nama_rekening);
            $sheet->setCellValue('D' . $row, isset($honorariumsPerSenat[$senat->id]) ? $honorariumsPerSenat[$senat->id] : 'N/A');

            // Terapkan style border untuk setiap sel pada baris ini
            foreach (range('A', 'D') as $column) {
                $sheet->getStyle($column . $row)->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]]);
            }

            $row++;
        }

        // Menyimpan file Excel dan mengembalikan response untuk diunduh
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Report_Dasar.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }



    public function printReportDetail(Request $request)
    {
        $senats = Senat::with(['user', 'golongan', 'komisi'])->get();
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $rapats = Rapat::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();

        $reportData = $this->generateReport($senats, $rapats);
        $kehadirans = $reportData['kehadirans'];
        $honorariumsPerRapat = $reportData['honorariumsPerRapat'];
        $honorariumsPerSenat = $reportData['honorariumsPerSenat'];
        $honors = $reportData['honors'];
        $pphs = $reportData['pphs'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'GP');
        $sheet->setCellValue('D1', 'Jabatan dalam Senat');
        $sheet->setCellValue('E1', 'Komisi');

        $columnIndex = 6;
        foreach ($rapats as $rapat) {
            $sheet->setCellValueByColumnAndRow($columnIndex, 1, strtoupper($rapat->komisi->komisi . ' - ' . date('d M Y', strtotime($rapat->tanggal))));
            $sheet->mergeCellsByColumnAndRow($columnIndex, 1, $columnIndex + 2, 1);
            $columnIndex += 3;
        }

        $totalHonorColumnIndex = $columnIndex;
        $totalHonorColumnTitle = 'TOTAL HONOR BULAN ';
        $sheet->setCellValueByColumnAndRow($totalHonorColumnIndex, 1, strtoupper($totalHonorColumnTitle));
        $sheet->mergeCellsByColumnAndRow($totalHonorColumnIndex, 1, $totalHonorColumnIndex + 2, 1);

        $honorColumnIndex = $totalHonorColumnIndex;
        $sheet->setCellValueByColumnAndRow($honorColumnIndex, 2, 'Honor');
        $sheet->setCellValueByColumnAndRow(++$honorColumnIndex, 2, 'PPH');
        $sheet->setCellValueByColumnAndRow(++$honorColumnIndex, 2, 'Diterima');

        $npwpColumnIndex = $totalHonorColumnIndex + 3;
        $npwpColumnTitle = 'NPWP';
        $sheet->setCellValueByColumnAndRow($npwpColumnIndex, 1, strtoupper($npwpColumnTitle));

        $columnIndex = 6;
        foreach ($rapats as $rapat) {
            $sheet->setCellValueByColumnAndRow($columnIndex, 2, 'Honor');
            $sheet->setCellValueByColumnAndRow($columnIndex + 1, 2, 'PPH');
            $sheet->setCellValueByColumnAndRow($columnIndex + 2, 2, 'Diterima');
            $columnIndex += 3;
        }

        $row = 3;
        foreach ($senats as $index => $senat) {
            $columnIndex = 1;
            $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $index + 1);
            $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $senat->name);
            $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $senat->golongan->golongan);
            $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $senat->jabatan);
            $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $senat->komisi->komisi);

            foreach ($rapats as $rapat) {
                $senat_id = $senat->id;
                $rapat_id = $rapat->id;
                $is_kehadiran = isset($kehadirans[$senat_id][$rapat_id]) ? $kehadirans[$senat_id][$rapat_id] : false;
                if ($is_kehadiran) {
                    $honorarium = isset($senat->golongan) ? $senat->golongan->honor : '-';
                    $pph = isset($senat->golongan) ? $senat->golongan->pph : '-';
                    $diterima = isset($honorariumsPerRapat[$rapat_id]) ? $honorariumsPerRapat[$rapat_id] : 'N/A';
                } else {
                    $honorarium = '-';
                    $pph = '-';
                    $diterima = '-';
                }
                $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $honorarium);
                $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $pph);
                $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $diterima);
            }

            $sheet->setCellValueByColumnAndRow($totalHonorColumnIndex, $row, isset($honors[$senat->id]) ? $honors[$senat->id] : 'N/A');
            $sheet->setCellValueByColumnAndRow($totalHonorColumnIndex + 1, $row, isset($pphs[$senat->id]) ? $pphs[$senat->id] : 'N/A');
            $sheet->setCellValueByColumnAndRow($totalHonorColumnIndex + 2, $row, isset($honorariumsPerSenat[$senat->id]) ? $honorariumsPerSenat[$senat->id] : 'N/A');
            $sheet->setCellValueByColumnAndRow($npwpColumnIndex, $row, $senat->NPWP);

            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);

        // Set the width of the NPWP column to 40
        $sheet->getColumnDimensionByColumn($npwpColumnIndex)->setWidth(40);


        $boldStyle = [
            'font' => ['bold' => true],
        ];
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($boldStyle);
        $sheet->getStyle('F1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalHonorColumnIndex + 2) . '1')->applyFromArray([
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $boldCenterStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalHonorColumnIndex + 2) . '1')->applyFromArray($boldCenterStyle);

        $sheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($npwpColumnIndex) . '1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // Ensure the NPWP column width is set to 40
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())->applyFromArray($borderStyle);

        $excel_file_path = public_path('storage/ReportDetail/report_detail.xlsx');

        if (!file_exists(dirname($excel_file_path))) {
            mkdir(dirname($excel_file_path), 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($excel_file_path);

        return response()->download($excel_file_path);
    }

    public function printReportPribadi(Request $request)
    {
        // Mendapatkan id_senat dari user yang sedang login
        $id_senat = Auth::user()->id_senat;
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $rapats = Rapat::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();

        // Dapatkan semua senat dengan relasi yang diperlukan
        $senats = Senat::with(['golongan', 'komisi'])->where('id', $id_senat)->get();



        // Report Data
        $reportData = $this->generateReport($senats, $rapats);
        $kehadirans = $reportData['kehadirans'];
        $honorariumsPerRapat = $reportData['honorariumsPerRapat'];
        $honorariumsPerSenat = $reportData['honorariumsPerSenat'];
        $honors = $reportData['honors'];
        $pphs = $reportData['pphs'];

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'GP');
        $sheet->setCellValue('C1', 'Jabatan dalam Senat');
        $sheet->setCellValue('D1', 'Komisi');

        // Set column headers for each meeting
        $columnIndex = 5; // Column starts from index 5 (column E)
        foreach ($rapats as $rapat) {
            $sheet->setCellValueByColumnAndRow($columnIndex, 1, $rapat->komisi->komisi . ' - ' . date('d M Y', strtotime($rapat->tanggal)));
            $sheet->mergeCellsByColumnAndRow($columnIndex, 1, $columnIndex + 2, 1); // Merge cells for meeting and date
            $columnIndex += 3; // Move to the next column
        }

        // Set total honor per month column
        $totalHonorColumnIndex = $columnIndex; // Save column index for Total Honor Per Bulan
        $sheet->setCellValueByColumnAndRow($totalHonorColumnIndex, 1, 'Total Honor Per Bulan');
        $sheet->mergeCellsByColumnAndRow($totalHonorColumnIndex, 1, $totalHonorColumnIndex + 2, 1); // Merge cells for total honor per month

        // Set honor, PPH, and received columns
        $honorColumnIndex = $totalHonorColumnIndex;
        $sheet->setCellValueByColumnAndRow($honorColumnIndex, 2, 'Honor');
        $sheet->setCellValueByColumnAndRow(++$honorColumnIndex, 2, 'PPH');
        $sheet->setCellValueByColumnAndRow(++$honorColumnIndex, 2, 'Diterima');

        // Set NPWP column
        $npwpColumnIndex = $totalHonorColumnIndex + 3; // Move to three columns after total honor per month
        $sheet->setCellValueByColumnAndRow($npwpColumnIndex, 1, 'NPWP');

        // Set column headers for honor, PPH, and received for each meeting
        $columnIndex = 5; // Column starts from index 5 (column E)
        foreach ($rapats as $rapat) {
            $sheet->setCellValueByColumnAndRow($columnIndex, 2, 'Honor');
            $sheet->setCellValueByColumnAndRow($columnIndex + 1, 2, 'PPH');
            $sheet->setCellValueByColumnAndRow($columnIndex + 2, 2, 'Diterima');
            $columnIndex += 3; // Move to the next column
        }

        // Fill in the data
        $row = 3; // Start from row 3
        foreach ($senats as $senat) {
            $columnIndex = 1; // Start from the first column
            $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $senat->name);
            $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $senat->golongan->golongan ?? '-');
            $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $senat->jabatan);
            $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $senat->komisi->komisi);

            // Fill in honorarium data per meeting
            foreach ($rapats as $rapat) {
                $rapat_id = $rapat->id;
                $is_kehadiran = isset($kehadirans[$senat->id][$rapat_id]) ? $kehadirans[$senat->id][$rapat_id] : false;
                if ($is_kehadiran) {
                    $honorarium = isset($senat->golongan) ? $senat->golongan->honor : '-';
                    $pph = isset($senat->golongan) ? $senat->golongan->pph : '-';
                    $diterima = isset($honorariumsPerRapat[$rapat_id]) ? $honorariumsPerRapat[$rapat_id] : 'N/A';
                } else {
                    $honorarium = '-';
                    $pph = '-';
                    $diterima = '-';
                }
                $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $honorarium);
                $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $pph);
                $sheet->setCellValueByColumnAndRow($columnIndex++, $row, $diterima);
            }

            // Fill in total honor per month, NPWP
            $sheet->setCellValueByColumnAndRow($totalHonorColumnIndex, $row, isset($honors[$senat->id]) ? $honors[$senat->id] : 'N/A');
            $sheet->setCellValueByColumnAndRow($totalHonorColumnIndex + 1, $row, isset($pphs[$senat->id]) ? $pphs[$senat->id] : 'N/A');
            $sheet->setCellValueByColumnAndRow($totalHonorColumnIndex + 2, $row, isset($honorariumsPerSenat[$senat->id]) ? $honorariumsPerSenat[$senat->id] : 'N/A');

            $sheet->setCellValueExplicitByColumnAndRow($npwpColumnIndex, $row, $senat->NPWP, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $row++;
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(20); // Nama
        $sheet->getColumnDimension('B')->setWidth(15); // GP
        $sheet->getColumnDimension('C')->setWidth(20); // Jabatan dalam Senat
        $sheet->getColumnDimension('D')->setWidth(20); // Komisi
        $sheet->getColumnDimensionByColumn($npwpColumnIndex)->setWidth(40);
        // Set text style for column headers
        $boldCenterStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($npwpColumnIndex) . '1')->applyFromArray($boldCenterStyle);

        // Set border for all cells
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())->applyFromArray($borderStyle);

        // Get Excel file path
        $excelFilePath = public_path('storage/ReportPribadi/report_pribadi.xlsx');

        // Ensure storage directory exists
        if (!file_exists(dirname($excelFilePath))) {
            mkdir(dirname($excelFilePath), 0755, true);
        }

        // Save Spreadsheet as Excel file
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Redirect to the Excel file
        return response()->download($excelFilePath);
    }



    private function generateReport($senats, $rapats)
    {
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

    public function reportDetail(Request $request)
    {
        $senats = Senat::with(['user', 'golongan', 'komisi'])->get();

        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $rapats = Rapat::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();

        $reportData = $this->generateReport($senats, $rapats);

        return view('content.honor.honor-detail', array_merge($reportData, [
            'senats' => $senats,
            'rapats' => $rapats,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]));
    }


    public function reportPribadi(Request $request)
    {

        $id_senat = Auth::user()->id_senat;


        $senat = Senat::with(['user', 'golongan', 'komisi'])->findOrFail($id_senat);


        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $rapats = Rapat::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();

        $reportData = $this->generateReport([$senat], $rapats);


        return view('content.honor.honor-pribadi', array_merge($reportData, [
            'senat' => $senat,
            'rapats' => $rapats,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]));
    }

    public function KehadiranSenat(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $idKomisi = $request->input('id_komisi');

        $kehadiran = Kehadiran::with(['senat', 'rapat.komisi'])
            ->whereHas('rapat', function ($query) use ($bulan, $tahun, $idKomisi) {
                if ($bulan) {
                    $query->whereMonth('tanggal', $bulan);
                }
                if ($tahun) {
                    $query->whereYear('tanggal', $tahun);
                }
                if ($idKomisi) {
                    $query->where('id_komisi', $idKomisi);
                }
            })
            ->get();

        $komisi = Komisi::all(); // Mengambil semua data Komisi

        return view('content.kehadiran.list-kehadiran', compact('kehadiran', 'bulan', 'tahun', 'idKomisi', 'komisi'));
    }



    public function Printkehadiran(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $idKomisi = $request->input('id_komisi');

        $kehadiran = Kehadiran::with(['senat', 'rapat'])
            ->whereHas('rapat', function ($query) use ($bulan, $tahun, $idKomisi) {
                if ($bulan) {
                    $query->whereMonth('tanggal', $bulan);
                }
                if ($tahun) {
                    $query->whereYear('tanggal', $tahun);
                }
                if ($idKomisi) {
                    $query->where('id_komisi', $idKomisi);
                }
            })
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Style
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        // Set Header Style
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        // Header
        $sheet->setCellValue('A1', 'Nama Senat');
        $sheet->setCellValue('B1', 'Status Kehadiran');
        $sheet->setCellValue('C1', 'Nama Rapat');
        $sheet->setCellValue('D1', 'Tanggal Rapat');

        // Set Column Width
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);

        // Data Style
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        // Data
        $row = 2;
        foreach ($kehadiran as $item) {
            $sheet->setCellValue('A' . $row, $item->senat->name);
            $sheet->setCellValue('B' . $row, $item->verifikasi);
            $sheet->setCellValue('C' . $row, $item->rapat->komisi->komisi);
            $sheet->setCellValue('D' . $row, Carbon::parse($item->rapat->tanggal)->format('d-m-Y'));
            // Set Data Style
            $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray($dataStyle);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'kehadiran_senat_' . $bulan . '_' . $tahun . '.xlsx';
        $temp_file = storage_path('app/public/reports/' . $fileName);

        // Pastikan direktori ada
        if (!file_exists(storage_path('app/public/reports'))) {
            mkdir(storage_path('app/public/reports'), 0755, true);
        }

        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}
