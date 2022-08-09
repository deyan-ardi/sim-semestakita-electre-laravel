<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TmpTagihanIuran extends Model
{
    use HasFactory;
    protected $table = 'tmp_tagihan_iuran';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'petugas_id',
    ];

    // Relationalship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'id');
    }

    public function tmp_detail_tagihan_iuran()
    {
        return $this->hasMany(TmpDetailTagihanIuran::class);
    }
}
