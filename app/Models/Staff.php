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
        'credentials',
        'bio',
        'photo_url',
        'photo_alt_text',
        'is_featured',
        'hotspot_tag',
        'languages_spoken',
        'years_experience',
        'on_call',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'languages_spoken' => 'array',
        'years_experience' => 'integer',
        'on_call' => 'boolean',
    ];
}
