<?php

namespace App\Support;

class AssessmentQuestions
{
    public static function getQuestions(): array
    {
        return [
            'academic_1' => [
                'dimension' => 'Akademik',
                'statement' => 'Saya dapat mengelola tugas kuliah yang sedang saya hadapi.',
            ],
            'academic_2' => [
                'dimension' => 'Akademik',
                'statement' => 'Saya memiliki cara belajar yang membantu saya mengikuti perkuliahan.',
            ],
            'academic_3' => [
                'dimension' => 'Akademik',
                'statement' => 'Saya cukup yakin dapat mencari bantuan saat mengalami kesulitan akademik.',
            ],
            'financial_1' => [
                'dimension' => 'Finansial',
                'statement' => 'Saya merasa kebutuhan penting untuk kuliah dan hidup sehari-hari saat ini cukup terpenuhi.',
            ],
            'financial_2' => [
                'dimension' => 'Finansial',
                'statement' => 'Saya memiliki gambaran yang cukup jelas tentang sumber biaya kuliah saya.',
            ],
            'financial_3' => [
                'dimension' => 'Finansial',
                'statement' => 'Saya tahu tempat atau orang yang dapat dihubungi jika mengalami kesulitan biaya.',
            ],
            'motivational_1' => [
                'dimension' => 'Motivasi',
                'statement' => 'Saya masih melihat tujuan yang berarti dalam perjalanan kuliah saya.',
            ],
            'motivational_2' => [
                'dimension' => 'Motivasi',
                'statement' => 'Saya memiliki energi yang cukup untuk menjalani aktivitas kuliah saat ini.',
            ],
            'motivational_3' => [
                'dimension' => 'Motivasi',
                'statement' => 'Saya terdorong untuk tetap terlibat dalam kegiatan perkuliahan.',
            ],
            'social_1' => [
                'dimension' => 'Sosial',
                'statement' => 'Saya memiliki teman atau kelompok di kampus yang dapat diajak berbicara.',
            ],
            'social_2' => [
                'dimension' => 'Sosial',
                'statement' => 'Saya merasa diterima sebagai bagian dari lingkungan kampus.',
            ],
            'social_3' => [
                'dimension' => 'Sosial',
                'statement' => 'Saya tahu kepada siapa saya dapat meminta dukungan ketika menghadapi kesulitan.',
            ],
        ];
    }
}
