<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'headline',
        'description',
        'highlights',
        'display_order',
        'monthly_rate',
        'transport_fee',
        'capacity_daily',
        'availability_status',
        'languages_supported',
        'analytics_tag',
        'is_active',
    ];

    protected $casts = [
        'highlights' => 'array',
        'monthly_rate' => 'decimal:2',
        'transport_fee' => 'decimal:2',
        'capacity_daily' => 'integer',
        'languages_supported' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
