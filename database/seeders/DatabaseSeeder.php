<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(\App\Services\ResilienceScoringService $scoringService): void
    {

        $user = User::updateOrCreate(
            ['email' => 'student@demo.com'],
            [
                'name' => 'Demo Student',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'semester' => 4,
                'faculty' => 'Fakultas Teknik',
                'major' => 'Informatika',
                'role' => 'student'
            ]
        );

        // Idempotency: clear previous demo baseline assessments for this user
        $user->assessments()->delete();

        // Create baseline answers (Weak in financial)
        $questions = \App\Support\AssessmentQuestions::getQuestions();
        $answers = [];
        foreach ($questions as $key => $q) {
            if ($q['dimension'] === 'financial') {
                $answers[$key] = 2; // Need attention
            } elseif ($q['dimension'] === 'academic') {
                $answers[$key] = 3; // Average
            } else {
                $answers[$key] = 4; // Good
            }
        }

        $scores = $scoringService->calculateScore($answers);

        // Set created_at to 15 days ago so the user can immediately try checking in again on demo
        $assessment = $user->assessments()->create([
            'raw_answers' => $answers,
            'score_academic' => $scores['academic'],
            'score_financial' => $scores['financial'],
            'score_motivational' => $scores['motivational'],
            'score_social' => $scores['social'],
            'total_resilience_score' => $scores['total'],
            'created_at' => now()->subDays(8),
            'updated_at' => now()->subDays(8),
        ]);


        \App\Models\AssessmentChat::create([
            'assessment_id' => $assessment->id,
            'role' => 'user',
            'message' => 'Saya merasa kesulitan membayar UKT karena orang tua sedang sakit.',
        ]);

        \App\Models\AssessmentChat::create([
            'assessment_id' => $assessment->id,
            'role' => 'ai',
            'message' => 'Saya mengerti ini situasi berat. Berdasarkan skor Anda, prioritas utama adalah mencari bantuan finansial.',
        ]);

        // Demo Workspace Data
        $note = $user->notes()->create([
            'title' => 'Dasar Rekayasa Perangkat Lunak',
            'content' => 'Rekayasa perangkat lunak adalah disiplin ilmu yang membahas semua aspek produksi perangkat lunak, mulai dari tahap awal spesifikasi sistem sampai pemeliharaan sistem setelah digunakan.',
            'course_name' => 'RPL'
        ]);

        $deck = $user->flashcardDecks()->create([
            'name' => 'Flashcard RPL',
            'source_note_id' => $note->id
        ]);

        $deck->flashcards()->createMany([
            ['question' => 'Apa itu Rekayasa Perangkat Lunak?', 'answer' => 'Disiplin ilmu yang membahas aspek produksi PL.', 'is_memorized' => true],
            ['question' => 'Kapan tahap akhir RPL?', 'answer' => 'Pemeliharaan sistem setelah digunakan.', 'is_memorized' => false],
        ]);

        $quiz = $user->quizzes()->create([
            'title' => 'Quiz: Dasar RPL',
            'source_note_id' => $note->id,
            'total_questions' => 2,
            'correct_count' => 1,
            'score' => 50,
            'completed_at' => now()
        ]);

        $quiz->questions()->create([
            'question' => 'Apa fokus utama Rekayasa Perangkat Lunak?',
            'option_a' => 'Hanya coding',
            'option_b' => 'Semua aspek produksi perangkat lunak',
            'option_c' => 'Perawatan perangkat keras',
            'option_d' => 'Desain grafis',
            'correct_option' => 'b',
            'explanation' => 'RPL mencakup semua aspek.',
            'user_answer' => 'b',
            'is_correct' => true
        ]);
        
        $quiz->questions()->create([
            'question' => 'Tahap apa setelah spesifikasi?',
            'option_a' => 'Pemeliharaan',
            'option_b' => 'Desain',
            'option_c' => 'Testing',
            'option_d' => 'Deployment',
            'correct_option' => 'b',
            'explanation' => 'Desain sistem',
            'user_answer' => 'a',
            'is_correct' => false
        ]);

        $user->update([
            'streak_count' => 3,
            'last_active_at' => now()
        ]);
    }
}
