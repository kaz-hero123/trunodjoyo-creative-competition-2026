<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceMatch extends Model
{
    protected $fillable = [
        'assessment_id', 'resource_id', 'match_reason', 'is_clicked'
    ];

    protected function casts(): array
    {
        return [
            'is_clicked' => 'boolean',
        ];
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
