<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'resource_type',
        'file_path',
        'external_url',
        'preview_image',
        'persona_tag',
        'display_order',
        'requires_login',
        'is_active',
    ];

    protected $casts = [
        'requires_login' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
