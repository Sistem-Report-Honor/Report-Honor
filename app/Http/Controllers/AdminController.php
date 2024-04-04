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

    public function reportDetail()
    {
        // Dapatkan semua senat dengan relasi yang diperlukan
        $senats = Senat::with(['user', 'golongan', 'komisi'])->get();

        // Dapatkan semua rapat
        $rapats = Rapat::all();

        // Inisialisasi array untuk menyimpan kehadiran dan honorarium per rapat
        $kehadirans = [];
        $honorariumsPerRapat = [];
        $honorariumsPerSenat = [];

        // Lakukan perulangan melalui semua senat
        foreach ($senats as $senat) {
            // Inisialisasi total honorarium untuk senat ini
            $totalHonorariumSenat = 0;

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
                    $honors = $golongan ? $golongan->honor : 0;
                    $pphs = $golongan ? $golongan->pph : 0;
                    $honorarium = $honors - $pphs;

                    // Tambahkan honorarium senat ke total honorarium rapat
                    $totalHonorariumSenat += $honorarium;

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
