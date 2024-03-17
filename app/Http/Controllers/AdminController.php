<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\User;
use App\Models\Senat;
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
        $roles = Role::all();
        return view('content.user.form-user',['golongans' => $golongans, 'roles'=>$roles]);
    }

    public function create(Request $request)
    {
        // Validasi input dari request
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required',
            'nip' => 'required|string|max:18|unique:senat',
            'no_rek' => 'required|unique:senat|string',
            'nama_rekening' => 'required|string',
            'id_golongan' => 'required|exists:golongan,id',
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
            'jabatan' => $request->jabatan,
            // Isi kolom lainnya sesuai kebutuhan
        ]);

        // Buat record baru di tabel 'User' dengan data yang berhubungan dengan 'Senat'
        $user = User::create([
            'name' => $request->name, // Pastikan 'name' ada dalam request
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Isi kolom lainnya sesuai kebutuhan
            'id_senat' => $senat->id, // Tautkan 'User' dengan 'Senat' menggunakan ID
        ]);
        $user->assignRole($request->role);

        // Redirect atau lakukan tindakan lainnya setelah berhasil membuat record
        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }
}