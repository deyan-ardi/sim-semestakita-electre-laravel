<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConfKriteria extends Model
{
    use HasFactory;
    protected $table = 'conf_kriteria';
    public $incrementing = false;
    protected $guarded = [];
}
