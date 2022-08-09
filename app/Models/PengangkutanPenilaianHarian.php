<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengangkutanPenilaianHarian extends Model
{
    use HasFactory;
    protected $table = 'pengangkutan_penilaian_harian';
    public $incrementing = false;
    protected $guarded = [];

    public function detail_pengangkutan_penilaian_harian()
    {
        return $this->hasMany('App\Models\DetailPengangkutanPenilaianHarian', 'pengangkutan_penilaian_harian_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function pegawai()
    {
        return $this->belongsTo('App\Models\User', 'pegawai_id', 'id');
    }
}
