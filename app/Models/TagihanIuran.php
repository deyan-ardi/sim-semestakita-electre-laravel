<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TagihanIuran extends Model
{
    use HasFactory;
    protected $table = 'tagihan_iuran';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'no_tagihan',
        'user_id',
        'tanggal',
        'deskripsi',
        'due_date',
        'status',
        'sub_total',
        'sub_total_denda',
        'total_tagihan',
    ];

    // Relationalship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tmp_detail_tagihan_iuran()
    {
        return $this->hasMany(TmpDetailTagihanIuran::class, 'tagihan_iuran_id', 'id');
    }
}
