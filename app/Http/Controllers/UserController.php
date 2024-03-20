<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Senat;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function kehadiran(){
        $id = Auth::user()->id_senat;
        $datas =  Kehadiran::where('id_senat',$id)->get();
        return view('content.absen.kehadiran-user',['datas'=>$datas]);
    }

    public function password(){
        return view('content.account.change-password');
    }

    public function detail(Request $request){
        return view('content.account.detail-account');
    }
}
