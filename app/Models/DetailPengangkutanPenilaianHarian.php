<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPengangkutanPenilaianHarian extends Model
{
    use HasFactory;
    protected $table = 'detail_pengangkutan_penilaian_harian';
    public $incrementing = false;
    protected $guarded = [];

    public function pengangkutan_penilaian_harian()
    {
        return $this->belongsTo('App\Models\PengangkutanPenilaianHarian', 'pengangkutan_penilaian_harian_id', 'id');
    }

    public function kriteria()
    {
        return $this->belongsTo('App\Models\Kriteria', 'kriteria_id', 'id');
    }
}
