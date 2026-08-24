<?php

namespace App\Services;

class ResilienceScoringService
{
    /**
     * Menghitung skor resiliensi dari jawaban Likert 1-5.
     * Mengembalikan array skor dimensi dan skor total.
     *
     * @param array $answers
     * @return array
     */
    public function calculateScore(array $answers): array
    {
        $dimensions = ['academic', 'financial', 'motivational', 'social'];
        $scores = [];
        $totalSum = 0;

        foreach ($dimensions as $dim) {
            $sum = 0;
            $count = 0;

            for ($i = 1; $i <= 3; $i++) {
                $key = "{$dim}_{$i}";
                // Validasi jawaban antara 1-5 (atau fallback 0 untuk perhitungan)
                $answer = isset($answers[$key]) ? (float) $answers[$key] : 0;
                
                $normalized = $answer / 5;
                $sum += $normalized;
                $count++;
            }

            // Rata-rata dari 3 jawaban yang sudah dinormalisasi * 100
            $dimScore = $count > 0 ? ($sum / $count) * 100 : 0;
            $scores[$dim] = round($dimScore, 2);
            $totalSum += $scores[$dim];
        }

        $scores['total'] = round($totalSum / 4, 2);

        return $scores;
    }
}
