<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id', 'question', 'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'explanation', 'user_answer', 'is_correct'
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
