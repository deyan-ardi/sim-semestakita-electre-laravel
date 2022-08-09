<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailRekapanHarian extends Model
{
    use HasFactory;
    protected $table = 'detail_rekapan_harian';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'kategori_id',
        'nama_kategori',
        'harga_kategori',
        'jenis_sampah',
        'rekapan_harian_id',
        'jumlah_sampah',
    ];

    // Relationalship
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function rekapan_harian()
    {
        return $this->belongsTo(RekapanHarian::class);
    }
}
