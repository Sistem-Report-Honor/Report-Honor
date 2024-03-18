<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'username',
        'password',
        'id_senat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke tabel Senat
    public function senat()
    {
        return $this->hasOne(Senat::class,'id','id_senat');
    }
}
