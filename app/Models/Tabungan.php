<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tabungan extends Model
{
    use HasFactory;
    protected $table = 'tabungan';
    public $incrementing = false;
    protected $guarded = [];

    // Relationalship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
