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
    public function run(\App\Services\ResilienceScoringService $scoringService, \App\Services\ResourceMatchingService $matchingService): void
    {
        $this->seedResources();

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
            'created_at' => now()->subDays(15),
            'updated_at' => now()->subDays(15),
        ]);

        $matches = $matchingService->match($user, $assessment);
        foreach ($matches as $match) {
            \App\Models\ResourceMatch::create([
                'assessment_id' => $assessment->id,
                'resource_id' => $match->resource->id,
                'match_reason' => $match->reason,
            ]);
        }

        \App\Models\AssessmentChat::create([
            'assessment_id' => $assessment->id,
            'role' => 'user',
            'message' => 'Saya merasa kesulitan membayar UKT karena orang tua sedang sakit.',
        ]);

        \App\Models\AssessmentChat::create([
            'assessment_id' => $assessment->id,
            'role' => 'ai',
            'message' => 'Saya mengerti ini situasi berat. Berdasarkan skor Anda, prioritas utama adalah mencari bantuan finansial. Silakan cek "Beasiswa Kurang Mampu FT" di daftar rekomendasi Anda.',
        ]);
    }

    private function seedResources(): void
    {
        $resources = [
            // Financial (> 1)
            ['title' => 'Beasiswa Kurang Mampu FT', 'type' => 'scholarship', 'description' => 'Bantuan UKT untuk mahasiswa Fakultas Teknik.', 'provider_name' => 'Fakultas Teknik', 'url' => 'https://ft.demo.ac.id/beasiswa', 'target_dimensions' => ['financial'], 'eligible_majors' => ['Informatika', 'Sistem Informasi'], 'min_semester' => 2, 'max_semester' => 8, 'is_active' => true, 'deadline' => now()->addDays(30)->toDateString()],
            ['title' => 'Beasiswa Prestasi UTM', 'type' => 'scholarship', 'description' => 'Beasiswa untuk mahasiswa berprestasi dari semua fakultas.', 'provider_name' => 'Universitas Trunojoyo', 'url' => 'https://utm.demo.ac.id/prestasi', 'target_dimensions' => ['financial', 'academic'], 'eligible_majors' => null, 'min_semester' => 1, 'max_semester' => 8, 'is_active' => true, 'deadline' => now()->addDays(14)->toDateString()],
            ['title' => 'Bantuan UKT Kemenag', 'type' => 'scholarship', 'description' => 'Bantuan UKT dari Kementerian Agama.', 'provider_name' => 'Kemenag', 'url' => 'https://kemenag.demo.id/bantuan', 'target_dimensions' => ['financial'], 'eligible_majors' => null, 'min_semester' => 3, 'max_semester' => 8, 'is_active' => true, 'deadline' => now()->addDays(10)->toDateString()],
            ['title' => 'Program Magang Berbayar', 'type' => 'career', 'description' => 'Magang paruh waktu di unit rektorat.', 'provider_name' => 'Rektorat', 'url' => 'https://karir.demo.ac.id', 'target_dimensions' => ['financial', 'social'], 'eligible_majors' => null, 'min_semester' => 4, 'max_semester' => 8, 'is_active' => true, 'deadline' => null],
            // Academic
            ['title' => 'Klinik Pemrograman', 'type' => 'academic_support', 'description' => 'Bimbingan khusus untuk mata kuliah pemrograman dasar dan lanjut.', 'provider_name' => 'Lab Informatika', 'url' => 'https://lab.demo.ac.id/klinik', 'target_dimensions' => ['academic'], 'eligible_majors' => ['Informatika'], 'min_semester' => 1, 'max_semester' => 6, 'is_active' => true, 'deadline' => null],
            ['title' => 'Kelas Tambahan Matematika', 'type' => 'academic_support', 'description' => 'Bimbingan kalkulus dan matematika diskrit.', 'provider_name' => 'Pusat Studi Matematika', 'url' => 'https://math.demo.ac.id', 'target_dimensions' => ['academic'], 'eligible_majors' => null, 'min_semester' => 1, 'max_semester' => 4, 'is_active' => true, 'deadline' => null],
            ['title' => 'Workshop Penulisan Jurnal', 'type' => 'academic_support', 'description' => 'Pelatihan menulis publikasi ilmiah.', 'provider_name' => 'LPPM', 'url' => 'https://lppm.demo.ac.id', 'target_dimensions' => ['academic'], 'eligible_majors' => null, 'min_semester' => 5, 'max_semester' => 8, 'is_active' => true, 'deadline' => now()->addDays(20)->toDateString()],
            ['title' => 'English Conversation Club', 'type' => 'community', 'description' => 'Latihan speaking bahasa Inggris rutin.', 'provider_name' => 'UPT Bahasa', 'url' => 'https://bahasa.demo.ac.id', 'target_dimensions' => ['academic', 'social'], 'eligible_majors' => null, 'min_semester' => 1, 'max_semester' => 8, 'is_active' => true, 'deadline' => null],
            // Motivational
            ['title' => 'Layanan Konseling Sebaya', 'type' => 'counseling', 'description' => 'Sesi curhat dengan peer counselor terlatih.', 'provider_name' => 'Pusat Bimbingan', 'url' => 'https://counseling.demo.ac.id', 'target_dimensions' => ['motivational', 'social'], 'eligible_majors' => null, 'min_semester' => 1, 'max_semester' => 8, 'is_active' => true, 'deadline' => null],
            ['title' => 'Sesi Motivasi Alumni', 'type' => 'community', 'description' => 'Mentoring dari alumni sukses.', 'provider_name' => 'Ikatan Alumni', 'url' => 'https://alumni.demo.ac.id', 'target_dimensions' => ['motivational', 'academic'], 'eligible_majors' => null, 'min_semester' => 5, 'max_semester' => 8, 'is_active' => true, 'deadline' => now()->addDays(7)->toDateString()],
            ['title' => 'Bimbingan Psikologis', 'type' => 'counseling', 'description' => 'Konseling psikolog profesional untuk kecemasan skripsi.', 'provider_name' => 'Klinik Psikologi', 'url' => 'https://klinik.demo.ac.id', 'target_dimensions' => ['motivational'], 'eligible_majors' => null, 'min_semester' => 7, 'max_semester' => 14, 'is_active' => true, 'deadline' => null],
            ['title' => 'Healing & Mindfulness', 'type' => 'counseling', 'description' => 'Sesi meditasi dan manajemen stres mingguan.', 'provider_name' => 'Unit Kesehatan', 'url' => 'https://sehat.demo.ac.id', 'target_dimensions' => ['motivational'], 'eligible_majors' => null, 'min_semester' => 1, 'max_semester' => 8, 'is_active' => true, 'deadline' => null],
            // Social
            ['title' => 'UKM Seni Nanggala', 'type' => 'community', 'description' => 'Wadah mahasiswa berseni dan berbudaya.', 'provider_name' => 'Kemahasiswaan', 'url' => 'https://ukm.demo.ac.id/seni', 'target_dimensions' => ['social'], 'eligible_majors' => null, 'min_semester' => 1, 'max_semester' => 8, 'is_active' => true, 'deadline' => null],
            ['title' => 'Relawan Mengajar Madura', 'type' => 'community', 'description' => 'Kegiatan sosial mengajar anak-anak pesisir.', 'provider_name' => 'BEM', 'url' => 'https://bem.demo.ac.id', 'target_dimensions' => ['social', 'motivational'], 'eligible_majors' => null, 'min_semester' => 1, 'max_semester' => 8, 'is_active' => true, 'deadline' => now()->addDays(5)->toDateString()],
            ['title' => 'Komunitas Pendaki Kampus', 'type' => 'community', 'description' => 'Jelajah alam bersama teman mahasiswa.', 'provider_name' => 'Mapala', 'url' => 'https://mapala.demo.ac.id', 'target_dimensions' => ['social'], 'eligible_majors' => null, 'min_semester' => 1, 'max_semester' => 8, 'is_active' => true, 'deadline' => null],
        ];

        foreach ($resources as $res) {
            \App\Models\Resource::updateOrCreate(['title' => $res['title']], $res);
        }
    }
}
