<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kriteria extends Model
{
    use HasFactory;
    protected $table = 'kriteria';
    public $incrementing = false;
    protected $guarded = [];

    public function detail_rekapan_penilaian()
    {
        return $this->hasMany('App\Models\DetailRekapanPenilaian', 'kriteria_id', 'id');
    }

    public function detail_pengangkutan_penilaian_harian()
    {
        return $this->hasMany('App\Models\DetailPengangkutanPenilaianHarian', 'kriteria_id', 'id');
    }
}
