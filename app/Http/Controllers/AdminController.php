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
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function table()
    {
        $users = User::with('senat')->get();
        return view('content.user.table-user', ['users' => $users]);
    }

    public function form()
    {
        $golongans = Golongan::all();
        $komisis = Komisi::all();
        $roles = Role::all();
        return view('content.user.form-user', ['golongans' => $golongans, 'komisis' => $komisis, 'roles' => $roles]);
    }

    public function rapat()
    {
        $rapats = Rapat::all();
        return view('content.honor.honor-detail', ['rapats' => $rapats]);
    }

  // Di dalam kelas AdminController

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




    // public function reportDetail()
    // {
    //     // Dapatkan semua senat dengan relasi yang diperlukan
    //     $senats = Senat::with(['user', 'golongan', 'komisi'])->get();

    //     // Dapatkan semua rapat
    //     $rapats = Rapat::all();

    //     $kehadiran = Kehadiran::where('verifikasi', 'Hadir')->get();

    //     // Menghitung jumlah kehadiran untuk setiap anggota rapat
    //     $jumlahKehadiran = [];
    //     foreach ($senats as $senat) {
    //         // Menghitung jumlah kehadiran berdasarkan id senat
    //         $jumlahKehadiran[$senat->id] = $kehadiran->where('id_senat', $senat->id)->count();
    //     }

    //     $honorPerSenat = [];
    //     $pphPerSenat = [];
    //     $honorariumsPerSenat = [];
    //     foreach ($senats as $senat) {
    //         $golongan = $senat->golongan;
    //         if ($golongan) {
    //             $honors = $golongan->honor;
    //             $pphs = $golongan->pph;
    //             $honorariumsPerSenat[$senat->id] = ($honors - $pphs) * ($jumlahKehadiran[$senat->id] ?? 0);
    //             $honorPerSenat[$senat->id] = $honors * ($jumlahKehadiran[$senat->id] ?? 0);
    //             $pphPerSenat[$senat->id] = $pphs * ($jumlahKehadiran[$senat->id] ?? 0);
    //         } else {
    //             // Atur nilai honorarium menjadi 0 jika golongan tidak ditemukan
    //             $honorariumsPerSenat[$senat->id] = 0;
    //             $honorPerSenat[$senat->id] = 0;
    //             $pphPerSenat[$senat->id] = 0;
    //         }
    //     }

    //     // Inisialisasi array untuk menyimpan kehadiran, pph, dan honor untuk setiap senat dalam setiap rapat
    //     $kehadirans = [];
    //     $pphs = [];
    //     $honors = [];

    //     // Lakukan perulangan melalui semua senat
    //     foreach ($senats as $senat) {
    //         // Inisialisasi array untuk rapat-rapat yang terkait dengan senat saat ini
    //         $kehadirans[$senat->id] = [];
    //         $pphs[$senat->id] = [];
    //         $honors[$senat->id] = [];

    //         // Lakukan perulangan melalui semua rapat
    //         foreach ($rapats as $rapat) {
    //             // Periksa apakah senat hadir dalam rapat yang sesuai
    //             $hadir = Kehadiran::where('id_senat', $senat->id)
    //                 ->where('id_rapat', $rapat->id)
    //                 ->exists();

    //             if ($hadir) {
    //                 // Jika senat hadir, dapatkan informasi pph dan honor
    //                 $golongan = $senat->golongan;
    //                 $pph = $golongan ? $golongan->pph : 0;
    //                 $honor = $golongan ? $golongan->honor : 0;

    //                 // Simpan informasi kehadiran, pph, dan honor dalam array
    //                 $kehadirans[$senat->id][$rapat->id] = true;
    //                 $pphs[$senat->id][$rapat->id] = $pph;
    //                 $honors[$senat->id][$rapat->id] = $honor;
    //             } else {
    //                 // Jika senat tidak hadir, atur pph dan honor menjadi 0
    //                 $kehadirans[$senat->id][$rapat->id] = false;
    //                 $pphs[$senat->id][$rapat->id] = 0;
    //                 $honors[$senat->id][$rapat->id] = 0;
    //             }
    //         }
    //     }

    //     // dd($kehadirans);

    //     $honorariumsPerRapat = [];

    //     // Lakukan perulangan melalui semua rapat
    //     foreach ($rapats as $rapat) {
    //         // Inisialisasi total honorarium untuk rapat ini
    //         $totalHonorariumRapat = 0;

    //         // Lakukan perulangan melalui semua kehadiran
    //         foreach ($kehadiran as $hadir) {
    //             // Periksa apakah kehadiran ini terkait dengan rapat yang sedang diproses
    //             if ($hadir->id_rapat == $rapat->id) {
    //                 // Dapatkan informasi golongan senat yang hadir
    //                 $senat = $senats->firstWhere('id', $hadir->id_senat);
    //                 $golongan = $senat->golongan;

    //                 if ($golongan) {
    //                     // Hitung honorarium untuk senat ini pada rapat ini
    //                     $honors = $golongan->honor;
    //                     $pphs = $golongan->pph;
    //                     $honorarium = ($honors - $pphs);
    //                 }
    //             }
    //         }

    //         // Simpan total honorarium untuk rapat ini dalam array
    //         $honorariumsPerRapat[$rapat->id] = $honorarium;
    //     }

    //     // Tampilkan data ke view
    //     return view('content.honor.honor-detail', [
    //         'senats' => $senats,
    //         'rapats' => $rapats,
    //         'kehadirans' => $kehadirans,
    //         'honorariumsPerRapat' => $honorariumsPerRapat,
    //         'honorariums' => $honorariumsPerSenat,
    //         'pphs' => $pphs,
    //         'honors' => $honors,
    //         'honorPerSenat' => $honorPerSenat,
    //         'pphPerSenat' => $pphPerSenat
    //     ]);
    // }


    public function reportDetail()
    {
        // Dapatkan semua senat dengan relasi yang diperlukan
        $senats = Senat::with(['user', 'golongan', 'komisi'])->get();
    {
        // Dapatkan semua senat dengan relasi yang diperlukan
        $senats = Senat::with(['user', 'golongan', 'komisi'])->get();

        // Dapatkan semua rapat
        $rapats = Rapat::all();
        // Dapatkan semua rapat
        $rapats = Rapat::all();

        // Inisialisasi array untuk menyimpan kehadiran dan honorarium per rapat
        $kehadirans = [];
        $honorariumsPerRapat = [];
        $honorariumsPerSenat = [];
        // Inisialisasi array untuk menyimpan kehadiran dan honorarium per rapat
        $kehadirans = [];
        $honorariumsPerRapat = [];
        $honorariumsPerSenat = [];

        // Lakukan perulangan melalui semua senat
        foreach ($senats as $senat) {
            // Inisialisasi total honorarium untuk senat ini
            $totalHonorariumSenat = 0;
        // Lakukan perulangan melalui semua senat
        foreach ($senats as $senat) {
            // Inisialisasi total honorarium untuk senat ini
            $totalHonorariumSenat = 0;

            // Inisialisasi array kehadiran untuk senat ini di setiap rapat
            $kehadirans[$senat->id] = [];
            // Inisialisasi array kehadiran untuk senat ini di setiap rapat
            $kehadirans[$senat->id] = [];

            // Lakukan perulangan melalui semua rapat
            foreach ($rapats as $rapat) {
                // Periksa apakah senat hadir dalam rapat yang sedang diproses
                $hadir = Kehadiran::where('id_senat', $senat->id)
                    ->where('id_rapat', $rapat->id)
                    ->where('verifikasi', 'Hadir')
                    ->exists();
            // Lakukan perulangan melalui semua rapat
            foreach ($rapats as $rapat) {
                // Periksa apakah senat hadir dalam rapat yang sedang diproses
                $hadir = Kehadiran::where('id_senat', $senat->id)
                    ->where('id_rapat', $rapat->id)
                    ->where('verifikasi', 'Hadir')
                    ->exists();

                // Simpan status kehadiran senat dalam rapat
                $kehadirans[$senat->id][$rapat->id] = $hadir;
                // Simpan status kehadiran senat dalam rapat
                $kehadirans[$senat->id][$rapat->id] = $hadir;

                if ($hadir) {
                    // Dapatkan informasi golongan senat yang hadir
                    $golongan = $senat->golongan;
                if ($hadir) {
                    // Dapatkan informasi golongan senat yang hadir
                    $golongan = $senat->golongan;

                    // Hitung honorarium untuk senat ini pada rapat ini
                    $honors = $golongan ? $golongan->honor : 0;
                    $pphs = $golongan ? $golongan->pph : 0;
                    $honorarium = $honors - $pphs;
                    // Hitung honorarium untuk senat ini pada rapat ini
                    $honors = $golongan ? $golongan->honor : 0;
                    $pphs = $golongan ? $golongan->pph : 0;
                    $honorarium = $honors - $pphs;

                    // Tambahkan honorarium senat ke total honorarium rapat
                    $totalHonorariumSenat += $honorarium;
                    // Tambahkan honorarium senat ke total honorarium rapat
                    $totalHonorariumSenat += $honorarium;

                    // Simpan honorarium per rapat
                    if (!isset($honorariumsPerRapat[$rapat->id])) {
                        $honorariumsPerRapat[$rapat->id] = [];
                    }
                    $honorariumsPerRapat[$rapat->id] = $honorarium;
                }
            }
                    // Simpan honorarium per rapat
                    if (!isset($honorariumsPerRapat[$rapat->id])) {
                        $honorariumsPerRapat[$rapat->id] = [];
                    }
                    $honorariumsPerRapat[$rapat->id] = $honorarium;
                }
            }

            // Simpan total honorarium untuk senat ini dalam array
            $honorariumsPerSenat[$senat->id] = $totalHonorariumSenat;
        }
            // Simpan total honorarium untuk senat ini dalam array
            $honorariumsPerSenat[$senat->id] = $totalHonorariumSenat;
        }

        // Tampilkan data ke view
        return view('content.honor.honor-detail', [
            'senats' => $senats,
            'rapats' => $rapats,
            'kehadirans' => $kehadirans,
            'honorariumsPerRapat' => $honorariumsPerRapat,
            'honorariumsPerSenat' => $honorariumsPerSenat,
        ]);
    }
        // Tampilkan data ke view
        return view('content.honor.honor-detail', [
            'senats' => $senats,
            'rapats' => $rapats,
            'kehadirans' => $kehadirans,
            'honorariumsPerRapat' => $honorariumsPerRapat,
            'honorariumsPerSenat' => $honorariumsPerSenat,
        ]);
    }




    public function create(Request $request)
    {
        // Validasi input dari request
        $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|unique:users',
            'password' => 'required',
            'nip' => 'required|string|max:18|unique:senat',
            'NPWP' => 'required|string|max:18|unique:senat',
            'no_rek' => 'required|unique:senat|string',
            'nama_rekening' => 'required|string',
            'id_golongan' => 'required|exists:golongan,id',
            'id_komisi' => 'required|exists:komisi,id',
            'jabatan' => 'required|string',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);

        // Buat record baru di tabel 'Senat'
        $senat = Senat::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'no_rek' => $request->no_rek,
            'nama_rekening' => $request->nama_rekening,
            'id_golongan' => $request->id_golongan,
            'id_komisi' => $request->id_komisi,
            'jabatan' => $request->jabatan,
            'NPWP' => $request->NPWP,
            // Isi kolom lainnya sesuai kebutuhan
        ]);

        // Buat record baru di tabel 'User' dengan data yang berhubungan dengan 'Senat'
        $user = User::create([
            'name' => $request->name, // Pastikan 'name' ada dalam request
            'username' => $request->username,
            'password' => Hash::make($request->password),
            // Isi kolom lainnya sesuai kebutuhan
            'id_senat' => $senat->id, // Tautkan 'User' dengan 'Senat' menggunakan ID
        ]);
        $user->assignRole($request->role);

        // Redirect atau lakukan tindakan lainnya setelah berhasil membuat record
        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user =  User::where('id', $id)->with('senat')->first();
        $golongan = Golongan::all();
        $komisi = Komisi::all();
        $role = Role::all();
        return redirect()->route('table.user')->with('success', 'User berhasil diupdate.');
    }

    public function delete($id)
    {
        $user = User::find($id);
        $senat =  Senat::find($user->id_senat);
        if (!$user && !$senat) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $user->delete();
        $senat->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
