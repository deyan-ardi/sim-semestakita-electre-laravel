<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapanHarian extends Model
{
    use HasFactory;
    protected $table = 'rekapan_harian';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'tanggal',
        'status',
        'kode_transaksi',
        'total_sampah',
        'total_pemasukan',
        'created_by',
        'updated_by',
    ];

    // Relationalship

    public function detail_rekapan_harian()
    {
        return $this->hasMany(DetailRekapanHarian::class);
    }
}
