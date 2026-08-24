<?php

namespace Tests\Unit;

use App\Models\Assessment;
use App\Services\ResilienceScoringService;
use PHPUnit\Framework\TestCase;

class ResilienceScoringServiceTest extends TestCase
{
    private ResilienceScoringService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ResilienceScoringService();
    }

    public function test_calculate_score_with_all_fives_returns_100()
    {
        $answers = $this->generateAnswers(5);
        $scores = $this->service->calculateScore($answers);

        $this->assertEquals(100.0, $scores['academic']);
        $this->assertEquals(100.0, $scores['financial']);
        $this->assertEquals(100.0, $scores['motivational']);
        $this->assertEquals(100.0, $scores['social']);
        $this->assertEquals(100.0, $scores['total']);
    }

    public function test_calculate_score_with_all_ones_returns_20()
    {
        // 1/5 = 0.2 * 100 = 20
        $answers = $this->generateAnswers(1);
        $scores = $this->service->calculateScore($answers);

        $this->assertEquals(20.0, $scores['academic']);
        $this->assertEquals(20.0, $scores['total']);
    }

    public function test_calculate_score_with_twos_returns_40()
    {
        // 2/5 = 0.4 * 100 = 40 (Boundary Berkembang)
        $answers = $this->generateAnswers(2);
        $scores = $this->service->calculateScore($answers);

        $this->assertEquals(40.0, $scores['academic']);
        $this->assertEquals(40.0, $scores['total']);
    }

    public function test_calculate_score_with_mix_returns_correct_value()
    {
        $answers = [
            'academic_1' => 3, 'academic_2' => 4, 'academic_3' => 3, // avg 3.33 -> 66.67
            'financial_1' => 5, 'financial_2' => 5, 'financial_3' => 5, // 100
            'motivational_1' => 1, 'motivational_2' => 2, 'motivational_3' => 1, // avg 1.33 -> 26.67
            'social_1' => 4, 'social_2' => 4, 'social_3' => 4, // 80
        ];

        $scores = $this->service->calculateScore($answers);

        $this->assertEquals(66.67, $scores['academic']);
        $this->assertEquals(100.0, $scores['financial']);
        $this->assertEquals(26.67, $scores['motivational']);
        $this->assertEquals(80.0, $scores['social']);
        
        $expectedTotal = round((66.67 + 100.0 + 26.67 + 80.0) / 4, 2);
        $this->assertEquals($expectedTotal, $scores['total']);
    }

    public function test_dimension_status_boundary()
    {
        $assessment = new Assessment();

        // Boundary Perlu Perhatian (< 40)
        $assessment->score_academic = '39.99';
        $this->assertEquals('Perlu Perhatian', $assessment->dimensionStatus('academic'));

        $assessment->score_academic = '0';
        $this->assertEquals('Perlu Perhatian', $assessment->dimensionStatus('academic'));

        // Boundary Berkembang (40 - 69.99)
        $assessment->score_academic = '40.00';
        $this->assertEquals('Berkembang', $assessment->dimensionStatus('academic'));

        $assessment->score_academic = '69.99';
        $this->assertEquals('Berkembang', $assessment->dimensionStatus('academic'));

        // Boundary Kuat (>= 70)
        $assessment->score_academic = '70.00';
        $this->assertEquals('Kuat', $assessment->dimensionStatus('academic'));

        $assessment->score_academic = '100.00';
        $this->assertEquals('Kuat', $assessment->dimensionStatus('academic'));
    }

    private function generateAnswers(int $value): array
    {
        $keys = [
            'academic_1', 'academic_2', 'academic_3',
            'financial_1', 'financial_2', 'financial_3',
            'motivational_1', 'motivational_2', 'motivational_3',
            'social_1', 'social_2', 'social_3'
        ];
        return array_fill_keys($keys, $value);
    }
}
