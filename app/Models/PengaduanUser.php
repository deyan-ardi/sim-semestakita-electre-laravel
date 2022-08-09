<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengaduanUser extends Model
{
    use HasFactory;
    protected $table = 'pengaduan_user';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'judul',
        'kategori',
        'gambar',
        'konten',
    ];

    // Relationalship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
