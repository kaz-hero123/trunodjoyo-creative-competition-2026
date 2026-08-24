<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashcardDeck extends Model
{
    protected $fillable = ['user_id', 'name', 'source_note_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sourceNote()
    {
        return $this->belongsTo(Note::class, 'source_note_id');
    }

    public function flashcards()
    {
        return $this->hasMany(Flashcard::class);
    }
}
