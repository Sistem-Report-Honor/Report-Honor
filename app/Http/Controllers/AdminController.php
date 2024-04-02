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

    public function reportDasar()
    {

    $senats = Senat::with(['user', 'golongan'])->get();
        
    // Mengambil data kehadiran dengan verifikasi 'Hadir'
    $kehadirans = Kehadiran::where('verifikasi', 'Hadir')->get();
    
    // Menghitung jumlah kehadiran untuk setiap anggota rapat
    $jumlahKehadiran = [];
    foreach ($senats as $senat) {
        // Menghitung jumlah kehadiran berdasarkan id senat
        $jumlahKehadiran[$senat->id] = $kehadirans->where('id_senat', $senat->id)->count();
    }
    
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

    return view('content.honor.honor-dasar', ['senats' => $senats, 'honorariums' => $honorariumsPerSenat]);
    }

    public function reportDetail() {
    // Dapatkan semua senat dengan relasi yang diperlukan
    $senats = Senat::with(['user', 'golongan', 'komisi'])->get();

    // Dapatkan semua rapat
    $rapats = Rapat::all();

    $kehadiran = Kehadiran::where('verifikasi', 'Hadir')->get();

        // Menghitung jumlah kehadiran untuk setiap anggota rapat
    $jumlahKehadiran = [];
        foreach ($senats as $senat) {
            // Menghitung jumlah kehadiran berdasarkan id senat
            $jumlahKehadiran[$senat->id] = $kehadiran->where('id_senat', $senat->id)->count();
    }
        
    $honorPerSenat = [];
    $pphPerSenat = [];
    $honorariumsPerSenat = [];
        foreach ($senats as $senat) {
             $golongan = $senat->golongan;
            if ($golongan) {
                $honors = $golongan->honor;
                $pphs = $golongan->pph;
                $honorariumsPerSenat[$senat->id] = ($honors - $pphs) * ($jumlahKehadiran[$senat->id] ?? 0);
                $honorPerSenat[$senat->id] = $honors * ($jumlahKehadiran[$senat->id] ?? 0);
                $pphPerSenat[$senat->id] = $pphs * ($jumlahKehadiran[$senat->id] ?? 0);
            } else {
                // Atur nilai honorarium menjadi 0 jika golongan tidak ditemukan
                $honorariumsPerSenat[$senat->id] = 0;
                $honorPerSenat[$senat->id] = 0;
                $pphPerSenat[$senat->id] = 0;
            }
    }

    // Inisialisasi array untuk menyimpan kehadiran, pph, dan honor untuk setiap senat dalam setiap rapat
    $kehadirans = [];
    $pphs = [];
    $honors = [];

    // Lakukan perulangan melalui semua senat
    foreach ($senats as $senat) {
        // Inisialisasi array untuk rapat-rapat yang terkait dengan senat saat ini
        $kehadirans[$senat->id] = [];
        $pphs[$senat->id] = [];
        $honors[$senat->id] = [];
    
        // Lakukan perulangan melalui semua rapat
        foreach ($rapats as $rapat) {
            // Periksa apakah senat hadir dalam rapat yang sesuai
            $hadir = Kehadiran::where('id_senat', $senat->id)
                              ->where('id_rapat', $rapat->id)
                              ->exists();
    
            if ($hadir) {
                // Jika senat hadir, dapatkan informasi pph dan honor
                $golongan = $senat->golongan;
                $pph = $golongan ? $golongan->pph : 0;
                $honor = $golongan ? $golongan->honor : 0;
    
                // Simpan informasi kehadiran, pph, dan honor dalam array
                $kehadirans[$senat->id][$rapat->id] = true;
                $pphs[$senat->id][$rapat->id] = $pph;
                $honors[$senat->id][$rapat->id] = $honor;
            } else {
                // Jika senat tidak hadir, atur pph dan honor menjadi 0
                $kehadirans[$senat->id][$rapat->id] = false;
                $pphs[$senat->id][$rapat->id] = 0;
                $honors[$senat->id][$rapat->id] = 0;
            }
        }
    }

    // dd($kehadirans);

    $honorariumsPerRapat = [];

    // Lakukan perulangan melalui semua rapat
    foreach ($rapats as $rapat) {
        // Inisialisasi total honorarium untuk rapat ini
        $totalHonorariumRapat = 0;

        // Lakukan perulangan melalui semua kehadiran
        foreach ($kehadiran as $hadir) {
            // Periksa apakah kehadiran ini terkait dengan rapat yang sedang diproses
            if ($hadir->id_rapat == $rapat->id) {
                // Dapatkan informasi golongan senat yang hadir
                $senat = $senats->firstWhere('id', $hadir->id_senat);
                $golongan = $senat->golongan;

                if ($golongan) {
                    // Hitung honorarium untuk senat ini pada rapat ini
                    $honors = $golongan->honor;
                    $pphs = $golongan->pph;
                    $honorarium = ($honors - $pphs);
                }
            }
        }

        // Simpan total honorarium untuk rapat ini dalam array
        $honorariumsPerRapat[$rapat->id] = $honorarium;
    }

    // Tampilkan data ke view
    return view('content.honor.honor-detail', [
        'senats' => $senats,
        'rapats' => $rapats,
        'kehadirans' => $kehadirans,
        'honorariumsPerRapat' => $honorariumsPerRapat,
        'honorariums' => $honorariumsPerSenat,
        'pphs' => $pphs,
        'honors' => $honors,
        'honorPerSenat' => $honorPerSenat,
        'pphPerSenat' => $pphPerSenat
    ]);
    }


    // public function reportDetail()
    // {

    //     $senats = Senat::with(['user', 'golongan'])->get();
    //     $rapats = Rapat::all();
        
    //     // Mengambil data kehadiran dengan verifikasi 'Hadir'
    //     $kehadirans = Kehadiran::where('verifikasi', 'Hadir')->get();

    //     // Menghitung jumlah kehadiran untuk setiap anggota rapat
    //     $jumlahKehadiran = [];
    //     foreach ($senats as $senat) {
    //         // Menghitung jumlah kehadiran berdasarkan id senat
    //         $jumlahKehadiran[$senat->id] = $kehadirans->where('id_senat', $senat->id)->count();
    //     }
        
    //     $honorariumsPerSenat = [];
    //     foreach ($senats as $senat) {
    //          $golongan = $senat->golongan;
    //         if ($golongan) {
    //             $honors = $golongan->honor;
    //             $pphs = $golongan->pph;
    //             $honorariumsPerSenat[$senat->id] = ($honors - $pphs) * ($jumlahKehadiran[$senat->id] ?? 0);
    //         } else {
    //             // Atur nilai honorarium menjadi 0 jika golongan tidak ditemukan
    //             $honorariumsPerSenat[$senat->id] = 0;
    //         }
    //     }

    //     $honorsPerSenat = [];
    //     $pphPerSenat = [];
   
        
    //     foreach ($senats as $senat) {
    //         // Lakukan pengecekan kehadiran untuk setiap senatt
    //         if ($rapat && $rapat->komisi_id == $senat->komisi_id) {
    //             // Lakukan pengisian nilai jika rapat dan komisi_id sesuai
    //             if ($kehadirans->where('id_senat', $senat->id)->isNotEmpty()) {
    //                 // Lakukan pengisian nilai jika ada kehadiran
    //                 $golongan = $senat->golongan;
    //                 $honors = $golongan->honor ?? 0;
    //                 $pphs = $golongan->pph ?? 0;
    //                 $honorsPerSenat[$senat->id] = $honors;
    //                 $pphPerSenat[$senat->id] = $pphs;
    //             } else {
    //                 // Jika tidak ada kehadiran, atur nilai menjadi 0
    //                 $honorsPerSenat[$senat->id] = 0;
    //                 $pphPerSenat[$senat->id] = 0;
    //             }
    //         } else {
    //             // Jika rapat atau komisi_id tidak sesuai, atur nilai menjadi 0
    //             $honorsPerSenat[$senat->id] = 0;
    //             $pphPerSenat[$senat->id] = 0;
    //         }
        
    //     }

    //     dd($honorsPerSenat, $pphPerSenat);
  
    //     return view('content.honor.honor-detail', ['senats' => $senats, 'rapats' => $rapats, 'honorariums' => $honorariumsPerSenat, 'pph' => $pphPerSenat, 'honor' => $honorsPerSenat]);
    // }    

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
        return redirect()->route('table.user')->with('success', 'User berhasil ditambahkan.');
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
        if (!$user && !$senat){
            return response()->json(['message' => 'Data not found'], 404);
        }

        $user->delete();
        $senat->delete();

        return response()->json(['message' => 'Data deleted successfully'], 200);
    }
}
