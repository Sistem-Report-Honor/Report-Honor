<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Senat extends Model
{
    use HasFactory;

    protected $table = 'senat';

    protected $fillable = [
        'name',
        'nip',
        'npwp',
        'no_rek',
        'nama_rekening',
        'id_golongan',
        'jabatan',
        'NPWP',
        'id_komisi',
    ];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_senat');
    }

    // Relasi ke tabel Golongan
    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_golongan');
    }

    public function komisi()
    {
        return $this->belongsTo(Komisi::class, 'id_komisi');
    }
}
