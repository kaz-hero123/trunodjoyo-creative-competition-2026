<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['user_id', 'title', 'source_note_id', 'total_questions', 'correct_count', 'score', 'completed_at'];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sourceNote()
    {
        return $this->belongsTo(Note::class, 'source_note_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
