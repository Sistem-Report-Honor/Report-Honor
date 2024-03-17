<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
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
        return $this->hasOne(Senat::class, 'id_senat', 'id');
    }
}
