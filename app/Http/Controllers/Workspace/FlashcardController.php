<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FlashcardDeck;
use App\Models\Flashcard;
use App\Models\Note;
use App\Http\Requests\StoreFlashcardDeckRequest;
use App\Http\Requests\StoreFlashcardRequest;
use App\Services\GeminiService;

class FlashcardController extends Controller
{
    public function index(Request $request)
    {
        $decks = $request->user()->flashcardDecks()->withCount('flashcards')->latest()->get();
        return view('workspace.flashcards.index', compact('decks'));
    }

    public function show(Request $request, FlashcardDeck $flashcardDeck)
    {
        if ($flashcardDeck->user_id !== $request->user()->id) abort(403);
        $flashcardDeck->load('flashcards');
        return view('workspace.flashcards.show', compact('flashcardDeck'));
    }

    public function store(StoreFlashcardDeckRequest $request)
    {
        $deck = $request->user()->flashcardDecks()->create($request->validated());
        return redirect()->route('workspace.flashcard-decks.show', $deck)->with('success', 'Deck berhasil dibuat.');
    }

    public function destroy(Request $request, FlashcardDeck $flashcardDeck)
    {
        if ($flashcardDeck->user_id !== $request->user()->id) abort(403);
        $flashcardDeck->delete();
        return redirect()->route('workspace.flashcard-decks.index')->with('success', 'Deck berhasil dihapus.');
    }

    public function storeCard(StoreFlashcardRequest $request, FlashcardDeck $flashcardDeck)
    {
        if ($flashcardDeck->user_id !== $request->user()->id) abort(403);
        $flashcardDeck->flashcards()->create($request->validated());
        return back()->with('success', 'Flashcard berhasil ditambahkan.');
    }

    public function generate(Request $request, FlashcardDeck $flashcardDeck, GeminiService $gemini)
    {
        if ($flashcardDeck->user_id !== $request->user()->id) abort(403);
        
        $note = $flashcardDeck->sourceNote;
        if (!$note) {
            return back()->with('error', 'Deck ini tidak terhubung dengan catatan apapun.');
        }

        $flashcards = $gemini->generateFlashcards($note->content);
        
        if (empty($flashcards)) {
            return back()->with('error', 'Gagal meng-generate flashcard. Silakan coba lagi.');
        }

        foreach ($flashcards as $card) {
            if (isset($card['question']) && isset($card['answer'])) {
                $flashcardDeck->flashcards()->create([
                    'question' => $card['question'],
                    'answer' => $card['answer']
                ]);
            }
        }

        return back()->with('success', 'Flashcard berhasil di-generate.');
    }

    public function toggleMemorized(Request $request, Flashcard $flashcard)
    {
        $deck = $flashcard->deck;
        if ($deck->user_id !== $request->user()->id) abort(403);
        
        $flashcard->update(['is_memorized' => !$flashcard->is_memorized]);
        return back();
    }
}
