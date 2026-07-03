<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'semester',
        'status',
        'no_whatsapp',
        'email',
        'foto',
        'ipk',
        'sks_tempuh',
    ];

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function cutiRecords()
    {
        return $this->hasMany(CutiRecord::class);
    }
}
