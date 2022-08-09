<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapanPenilaian extends Model
{
    use HasFactory;
    protected $table = 'rekapan_penilaian';
    public $incrementing = false;
    protected $guarded = [];

    public function detail_rekapan_penilaian()
    {
        return $this->hasMany('App\Models\DetailRekapanPenilaian', 'rekapan_penilaian_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
