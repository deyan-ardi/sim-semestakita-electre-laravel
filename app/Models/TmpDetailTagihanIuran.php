<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TmpDetailTagihanIuran extends Model
{
    use HasFactory;
    protected $table = 'tmp_detail_tagihan_iuran';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'tmp_tagihan_iuran_id',
        'tagihan_iuran_id',
        'no_pembayaran',
    ];

    // Relationalship
    public function tmp_tagihan_iuran()
    {
        return $this->belongsTo(TmpTagihanIuran::class);
    }

    public function tagihan_iuran()
    {
        return $this->belongsTo(TagihanIuran::class, 'tagihan_iuran_id', 'id');
    }
}
