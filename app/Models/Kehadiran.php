<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_rapat',
        'id_senat',
        'waktu',
        'status',
    ];

    /**
     * Relationship with Rapat model
     */
    public function rapat()
    {
        return $this->belongsTo(Rapat::class, 'id_rapat');
    }

    /**
     * Relationship with Senat model
     */
    public function senat()
    {
        return $this->belongsTo(Senat::class, 'id_senat');
    }
}
