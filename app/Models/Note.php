<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'course_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flashcardDecks()
    {
        return $this->hasMany(FlashcardDeck::class, 'source_note_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'source_note_id');
    }
}
