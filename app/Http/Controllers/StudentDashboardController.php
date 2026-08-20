<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class StudentDashboardController extends Controller {
    public function index(Request $request) {
        $user = $request->user();
        $assessments = $user->assessments()->latest()->get();
        $latestAssessment = $assessments->first();
        $assessmentHistory = $assessments;
        $activeMatches = $latestAssessment ? $latestAssessment->matches()->with('resource')->get() : collect();
        $nextCheckInAt = $latestAssessment ? $latestAssessment->created_at->addDays(14) : null;
        
        return view('student.dashboard', compact('latestAssessment', 'assessmentHistory', 'activeMatches', 'nextCheckInAt'));
    }
}
