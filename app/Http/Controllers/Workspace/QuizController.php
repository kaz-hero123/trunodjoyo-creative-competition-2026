<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Note;
use App\Http\Requests\SubmitQuizRequest;
use App\Services\GeminiService;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function history(Request $request)
    {
        $quizzes = $request->user()->quizzes()->latest()->get();
        return view('workspace.quizzes.history', compact('quizzes'));
    }

    public function show(Request $request, Quiz $quiz)
    {
        if ($quiz->user_id !== $request->user()->id) abort(403);
        
        if ($quiz->completed_at) {
            return redirect()->route('workspace.quizzes.review', $quiz);
        }
        
        $quiz->load('questions');
        return view('workspace.quizzes.show', compact('quiz'));
    }

    public function review(Request $request, Quiz $quiz)
    {
        if ($quiz->user_id !== $request->user()->id) abort(403);
        if (!$quiz->completed_at) abort(404);
        
        $quiz->load('questions');
        return view('workspace.quizzes.review', compact('quiz'));
    }

    public function submit(SubmitQuizRequest $request, Quiz $quiz)
    {
        if ($quiz->user_id !== $request->user()->id) abort(403);
        if ($quiz->completed_at) abort(400, 'Quiz has already been completed.');

        $answers = $request->validated('answers');
        
        DB::transaction(function () use ($quiz, $answers) {
            $correctCount = 0;
            
            foreach ($quiz->questions as $question) {
                $userAnswer = $answers[$question->id] ?? null;
                $isCorrect = $userAnswer === $question->correct_option;
                
                $question->update([
                    'user_answer' => $userAnswer,
                    'is_correct' => $isCorrect
                ]);
                
                if ($isCorrect) {
                    $correctCount++;
                }
            }
            
            $score = $quiz->questions->count() > 0 ? round(($correctCount / $quiz->questions->count()) * 100) : 0;
            
            $quiz->update([
                'correct_count' => $correctCount,
                'score' => $score,
                'completed_at' => now()
            ]);
        });
        
        return redirect()->route('workspace.quizzes.review', $quiz)->with('success', 'Quiz disubmit.');
    }

    public function generate(Request $request, GeminiService $gemini)
    {
        $request->validate([
            'note_id' => 'required|exists:notes,id',
        ]);
        
        $note = Note::findOrFail($request->note_id);
        if ($note->user_id !== $request->user()->id) abort(403);

        $generatedData = $gemini->generateQuiz($note->content);
        
        if (empty($generatedData)) {
            return back()->with('error', 'Gagal meng-generate quiz. Coba lagi.');
        }

        $quiz = null;
        DB::transaction(function () use ($request, $note, $generatedData, &$quiz) {
            $quiz = $request->user()->quizzes()->create([
                'title' => 'Quiz: ' . $note->title,
                'source_note_id' => $note->id,
                'total_questions' => count($generatedData)
            ]);
            
            foreach ($generatedData as $q) {
                if (isset($q['question']) && isset($q['correct_option'])) {
                    $quiz->questions()->create([
                        'question' => $q['question'],
                        'option_a' => $q['option_a'] ?? '',
                        'option_b' => $q['option_b'] ?? '',
                        'option_c' => $q['option_c'] ?? '',
                        'option_d' => $q['option_d'] ?? '',
                        'correct_option' => $q['correct_option'],
                        'explanation' => $q['explanation'] ?? ''
                    ]);
                }
            }
        });
        
        if ($quiz) {
            return redirect()->route('workspace.quizzes.show', $quiz)->with('success', 'Quiz berhasil dibuat.');
        }

        return back()->with('error', 'Gagal menyimpan quiz.');
    }
}
