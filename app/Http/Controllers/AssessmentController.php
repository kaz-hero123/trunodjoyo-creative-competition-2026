<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreAssessmentRequest;
use App\Http\Requests\StoreAssessmentChatRequest;
use App\Models\Assessment;
use App\Models\AssessmentChat;
use App\Models\ResourceMatch;
use App\Services\ResilienceScoringService;
use App\Services\ResourceMatchingService;
use App\Services\GeminiService;
use App\Support\AssessmentQuestions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AssessmentController extends Controller {
    public function create(Request $request) {
        $user = $request->user();
        $latest = $user->assessments()->latest()->first();
        if ($latest && $latest->created_at->diffInDays(now()) < 14) {
            return redirect()->route('dashboard')->with('error', 'Check-in berikutnya tersedia setelah 14 hari.');
        }
        $questionsByDimension = collect(AssessmentQuestions::getQuestions())->groupBy('dimension');
        $nextCheckInAt = $latest ? $latest->created_at->addDays(14) : null;
        return view('student.check-in', compact('questionsByDimension', 'nextCheckInAt'));
    }

    public function store(StoreAssessmentRequest $request, ResilienceScoringService $scoringService, ResourceMatchingService $matchingService) {
        $user = $request->user();
        
        $lock = \Illuminate\Support\Facades\Cache::lock('check-in:' . $user->id, 10);
        
        if (! $lock->get()) {
            return redirect()->route('dashboard')->with('error', 'Permintaan Anda sedang diproses. Mohon tunggu sesaat.');
        }
        
        try {
            $latest = $user->assessments()->latest()->first();
            if ($latest && $latest->created_at->diffInDays(now()) < 14) {
                return redirect()->route('dashboard')->with('error', 'Check-in berikutnya tersedia setelah 14 hari.');
            }
            
            $answers = $request->validated('answers');
            
            $assessment = DB::transaction(function() use ($user, $answers, $scoringService, $matchingService) {
                $scores = $scoringService->calculateScore($answers);
                
                $assessment = $user->assessments()->create([
                    'raw_answers' => $answers,
                    'score_academic' => $scores['academic'],
                    'score_financial' => $scores['financial'],
                    'score_motivational' => $scores['motivational'],
                    'score_social' => $scores['social'],
                    'total_resilience_score' => $scores['total'],
                ]);
                
                $matches = $matchingService->match($user, $assessment);
                foreach ($matches as $match) {
                    ResourceMatch::create([
                        'assessment_id' => $assessment->id,
                        'resource_id' => $match->resource->id,
                        'match_reason' => $match->reason,
                    ]);
                }
                return $assessment;
            });
            
            return redirect()->route('results.show', $assessment);
        } finally {
            $lock->release();
        }
    }

    public function show(Assessment $assessment, Request $request) {
        if ($assessment->user_id !== $request->user()->id) {
            abort(403);
        }
        $matches = $assessment->matches()->with('resource')->get();
        $previousAssessment = $request->user()->assessments()->where('id', '<', $assessment->id)->latest()->first();
        
        $chatHistory = AssessmentChat::where('assessment_id', $assessment->id)->orderBy('created_at')->get()->toArray();
        return view('student.results', compact('assessment', 'matches', 'previousAssessment', 'chatHistory'));
    }

    public function chat(StoreAssessmentChatRequest $request, Assessment $assessment, GeminiService $geminiService) {
        if ($assessment->user_id !== $request->user()->id) {
            abort(403);
        }
        
        $message = $request->validated('message');
        
        AssessmentChat::create([
            'assessment_id' => $assessment->id,
            'role' => 'user',
            'message' => $message,
            'created_at' => now(),
        ]);
        
        $history = AssessmentChat::where('assessment_id', $assessment->id)->orderBy('created_at')->get()->toArray();
        
        $response = $geminiService->chat($assessment, $history);
        
        AssessmentChat::create([
            'assessment_id' => $assessment->id,
            'role' => 'ai',
            'message' => $response['advisor_response'],
            'created_at' => now(),
        ]);
        
        return redirect()->route('results.show', $assessment);
    }
}
