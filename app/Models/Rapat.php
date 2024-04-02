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
        'kode_unik',
        'id_komisi',
        'tanggal',
        'jam',
        'qr_code',
        'time_expired',
    ];

    public function komisi(){
        return $this->belongsTo(Komisi::class, 'id_komisi');
    }
    
}
