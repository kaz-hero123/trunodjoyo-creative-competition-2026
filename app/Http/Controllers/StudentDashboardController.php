<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class StudentDashboardController extends Controller {
    public function index(Request $request) {
        $user = $request->user();
        $assessments = $user->assessments()->latest()->get();
        $latestAssessment = $assessments->first();
        $assessmentHistory = $assessments;
        $nextCheckInAt = $latestAssessment ? $latestAssessment->created_at->addDays(7) : null;
        
        $streak = $user->streak_count;
        $notesCount = $user->notes()->count();
        $recentQuizzes = $user->quizzes()->latest()->take(3)->get();
        
        return view('student.dashboard.dashboard', compact('latestAssessment', 'assessmentHistory', 'nextCheckInAt', 'streak', 'notesCount', 'recentQuizzes'));
    }
}
