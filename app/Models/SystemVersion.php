<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemVersion extends Model
{
    use HasFactory;
    protected $table = 'system_version';
    protected $fillable = [
        'kode_versi',
        'nama_versi',
        'tanggal_rilis',
        'konten',
    ];
}
