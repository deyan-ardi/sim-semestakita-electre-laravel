<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_kategori',
        'jenis_sampah',
        'harga_beli',
        'total_sampah',
        'created_by',
        'updated_by',
    ];

    // Relationalship
    public function detail_rekapan_sampah()
    {
        return $this->hasMany(DetailRekapanSampah::class);
    }
    public function detail_rekapan_harian()
    {
        return $this->hasMany(DetailRekapanHarian::class);
    }
}
