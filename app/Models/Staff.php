<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'bio',
        'photo_url',
        'photo_alt_text',
        'is_featured',
        'hotspot_tag',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
