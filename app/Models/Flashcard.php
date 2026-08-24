<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    protected $fillable = ['flashcard_deck_id', 'question', 'answer', 'is_memorized'];

    protected function casts(): array
    {
        return [
            'is_memorized' => 'boolean',
        ];
    }

    public function deck()
    {
        return $this->belongsTo(FlashcardDeck::class, 'flashcard_deck_id');
    }
}
