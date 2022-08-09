<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogActivity extends Model
{
    use HasFactory;
    protected $table = 'log_activity';

    protected $fillable = [
        'ip_address',
        'user_id',
        'target_user',
        'previous_url',
        'current_url',
        'file',
        'action',
    ];

    // Relationalship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
