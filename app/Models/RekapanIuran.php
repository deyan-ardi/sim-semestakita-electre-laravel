<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapanIuran extends Model
{
    use HasFactory;
    protected $table = 'rekapan_iuran';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'tanggal',
        'user_id',
        'no_tagihan',
        'no_pembayaran',
        'deskripsi',
        'sub_total',
        'sub_total_denda',
        'status_denda',
        'total_tagihan',
    ];

    // Relationalship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
