<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'user_id', 'raw_answers', 'score_academic', 'score_financial', 
        'score_motivational', 'score_social', 'total_resilience_score',
        'is_baseline'
    ];

    protected function casts(): array
    {
        return [
            'raw_answers' => 'array',
            'score_academic' => 'decimal:2',
            'score_financial' => 'decimal:2',
            'score_motivational' => 'decimal:2',
            'score_social' => 'decimal:2',
            'total_resilience_score' => 'decimal:2',
            'is_baseline' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dimensionStatus(string $dimension): string
    {
        $scoreField = 'score_' . $dimension;
        $score = $this->$scoreField ?? 0;

        if ($score >= 70) {
            return 'Kuat';
        }

        if ($score >= 40) {
            return 'Berkembang';
        }

        return 'Perlu Perhatian';
    }
}
