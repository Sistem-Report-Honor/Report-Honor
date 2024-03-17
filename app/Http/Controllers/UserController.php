<?php

namespace App\Http\Controllers;

use App\Models\Senat;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function table(){
        $user = User::with('');
        return view('content.user.table-user',['users' => $user]);
    }

    public function form(){
        return view('content.user.form-user');
    }

    public function create(Request $request){
        $senat = Senat::create([
            
        ]);
        $user = User::create([
            
        ]);
    }
}
