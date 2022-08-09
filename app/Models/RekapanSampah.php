<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapanSampah extends Model
{
    use HasFactory;
    protected $table = 'rekapan_sampah';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'kode_transaksi',
        'total_sampah',
        'total_beli',
        'created_by',
        'updated_by',
    ];

    // Relationalship
    public function detail_rekapan_sampah()
    {
        return $this->hasMany(DetailRekapanSampah::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
