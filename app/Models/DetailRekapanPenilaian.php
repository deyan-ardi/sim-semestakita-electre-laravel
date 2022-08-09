<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailRekapanPenilaian extends Model
{
    use HasFactory;
    protected $table = 'detail_rekapan_penilaian';
    public $incrementing = false;
    protected $guarded = [];

    public function rekapan_penilaian()
    {
        return $this->belongsTo('App\Models\RekapanPenilaian', 'rekapan_penilaian_id', 'id');
    }

    public function kriteria()
    {
        return $this->belongsTo('App\Models\Kriteria', 'kriteria_id', 'id');
    }
}
