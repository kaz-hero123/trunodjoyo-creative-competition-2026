<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Resource extends Model
{
    protected $fillable = [
        'type', 'title', 'description', 'provider_name', 'url', 'contact_info',
        'deadline', 'target_dimensions', 'min_semester', 'max_semester', 'eligible_majors', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'target_dimensions' => 'array',
            'eligible_majors' => 'array',
            'deadline' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function matches()
    {
        return $this->hasMany(ResourceMatch::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
