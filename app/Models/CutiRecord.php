<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'status_notif',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
