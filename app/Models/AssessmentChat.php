<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentChat extends Model
{
    protected $fillable = [
        'assessment_id', 'role', 'message'
    ];
    
    // Disable updated_at according to blueprint "created_at" only
    const UPDATED_AT = null;

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
