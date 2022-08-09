<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemNotification extends Model
{
    use HasFactory;
    protected $table = 'system_notification';

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
        'key',
        'konten',
        'status',
    ];

    // Relationalship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
