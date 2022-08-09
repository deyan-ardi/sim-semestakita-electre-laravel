<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artikel extends Model
{
    use HasFactory;
    protected $table = 'artikel';
    public $incrementing = false;
    protected $guarded = [];

    // Slug
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'judul',
            ],
        ];
    }
}
