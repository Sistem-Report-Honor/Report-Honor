<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table ='rapat';
    protected $fillable = [
        'nama',
        'kode_unik',
        'tanggal',
        'jam',
        'qr_code',
        'time_expired',
    ];

    /**
     * Relationship with Golongan model
     */
    
}
