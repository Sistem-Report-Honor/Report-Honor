<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Senat;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function kehadiran()
    {
        $id = Auth::user()->id_senat;
        $datas =  Kehadiran::where('id_senat', $id)->get();
        return view('content.absen.kehadiran-user', ['datas' => $datas]);
    }

    public function passwordForm()
    {
        return view('content.account.change-password');
    }


    public function changePassword(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ]);

        $userId = Auth::id();

        $user = User::find($userId);
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Kata sandi saat ini salah.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return view('content.account.detail-account')->with('success', 'Kata sandi berhasil diubah.');

    }


    public function detail(Request $request)
    {
        $user = Auth::user();
        return view('content.account.detail-account', ['user' => $user]);
    }
}
