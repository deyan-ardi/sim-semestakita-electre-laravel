<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapanPenarikanTabungan extends Model
{
    use HasFactory;
    protected $table = 'rekapan_penarikan_tabungan';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'no_penarikan',
        'total_penarikan',
        'created_by',
        'updated_by',
    ];

    // Relationalship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
