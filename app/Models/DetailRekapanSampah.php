<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailRekapanSampah extends Model
{
    use HasFactory;
    protected $table = 'detail_rekapan_sampah';
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
        'rekapan_sampah_id',
        'jumlah_sampah',
        'sub_total',
    ];

    // Relationalship
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function rekapan_sampah()
    {
        return $this->belongsTo(RekapanSampah::class);
    }
}
