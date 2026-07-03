<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontakLayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kontak',
        'jabatan',
        'no_whatsapp',
        'pesan_template',
        'is_active',
    ];
}
