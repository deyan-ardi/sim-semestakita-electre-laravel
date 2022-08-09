<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembayaranRutin extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_rutin';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nama_pembayaran',
        'deskripsi',
        'total_biaya',
        'tgl_generate',
        'durasi_pembayaran',
        'created_by',
        'updated_by',
    ];

    // Relatinalship
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
