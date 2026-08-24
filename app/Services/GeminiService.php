<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private const EMERGENCY_KEYWORDS = [
        'bunuh diri', 'depresi', 'ingin mati', 'mengakhiri hidup',
        'tidak kuat lagi', 'nyakitin diri', 'menyakiti diri',
        'overdosis', 'hopeless', 'suicide'
    ];

    /**
     * Best-effort pattern matching untuk mencegah kebocoran diagnosis medis.
     * Ini bukan jaminan mutlak, melainkan penahan wajib untuk klaim yang paling jelas.
     */
    private const DIAGNOSIS_KEYWORDS = [
        'didiagnosis', 'anda menderita', 'kamu menderita', 'gangguan jiwa',
        'schizophrenia', 'bipolar', 'skizofrenia', 'gangguan kecemasan', 
        'adhd', 'autisme', 'clinical depression', 'depresi klinis',
        'mengalami ptsd', 'post-traumatic stress', 'mayor depressive', 'depressive disorder'
    ];

    private const EMERGENCY_RESPONSE = "Saya mengerti kamu sedang berada di situasi yang sangat berat. Keselamatanmu adalah yang utama. Sistem ini bukan untuk penanganan krisis. Mohon segera hubungi layanan darurat psikologi 24 jam gratis di 119 (ext. 8) atau kunjungi healing119.id. Kamu juga dapat menghubungi Pusat Konseling UTM untuk pendampingan lebih lanjut. Kamu tidak sendirian.";

    private const FALLBACK_RESPONSE = "Terima kasih sudah berbagi. Sistem saya sedang sibuk, namun tetap perhatikan daftar bantuan kampus di bawah yang mungkin bisa membantumu.";

    /**
     * Memproses pesan dari user dan mengembalikan response AI atau fallback.
     */
    public function chat(Assessment $assessment, array $history): array
    {
        // 1. Dapatkan pesan terbaru (pesan user yang baru disubmit)
        $latestMessage = end($history);
        $userText = $latestMessage['role'] === 'user' ? $latestMessage['message'] : '';

        // 2. Pre-filter indikasi darurat (Lapis 1)
        if ($this->containsEmergencyKeywords($userText)) {
            return ['advisor_response' => self::EMERGENCY_RESPONSE];
        }

        // 3. Tentukan dimensi terlemah
        $weakestDimension = $this->getWeakestDimension($assessment);

        // 4. Siapkan System Prompt (Lapis 2)
        $systemPrompt = $this->buildSystemPrompt($weakestDimension);

        // 5. Panggil Gemini API
        return $this->callGeminiApi($systemPrompt, $history, $assessment->user);
    }

    private function containsEmergencyKeywords(string $text): bool
    {
        $lowerText = strtolower($text);
        foreach (self::EMERGENCY_KEYWORDS as $keyword) {
            if (str_contains($lowerText, $keyword)) {
                return true;
            }
        }
        return false;
    } 

    private function getWeakestDimension(Assessment $assessment): string
    {
        $dimensions = [
            'academic' => $assessment->score_academic,
            'financial' => $assessment->score_financial,
            'motivational' => $assessment->score_motivational,
            'social' => $assessment->score_social,
        ];
        
        asort($dimensions); // Urutkan dari yang terendah
        $weakest = array_key_first($dimensions);
        
        $labels = [
            'academic' => 'Akademik',
            'financial' => 'Finansial',
            'motivational' => 'Motivasi',
            'social' => 'Sosial',
        ];

        return $labels[$weakest];
    }

    private function buildSystemPrompt(string $weakestDimension): string
    {
        return <<<PROMPT
Anda adalah AI Advisor Akademik terarah untuk mahasiswa Indonesia, bukan chatbot umum.
Pengguna sedang membahas masalah pada aspek terlemah mereka berdasarkan kuesioner, yaitu dimensi: {$weakestDimension}.
Gunakan riwayat percakapan sebelumnya untuk memahami konteks.
Keluarkan HANYA JSON valid sesuai schema berikut:
{
  "advisor_response": "pesan balasan Anda di sini",
  "contains_clinical_claim": true/false
}
Berikan saran rasional, praktis, dan akademis pada field "advisor_response".
DILARANG memberikan diagnosis medis, nasihat klinis, prediksi dropout, atau janji finansial.
Jika pengguna menunjukkan indikasi darurat terkait kesehatan mental (seperti ideasi bunuh diri atau depresi berat), jangan melakukan diagnosis. Tampilkan batasan layanan serta arahkan untuk menghubungi layanan darurat setempat atau layanan konseling kampus resmi.
Jangan mengubah, menghitung, atau menafsirkan ulang skor, status, eligibility, maupun pemilihan resource.
PENTING: Isi field "contains_clinical_claim" dengan nilai true HANYA JIKA "advisor_response" yang Anda buat mengandung klaim, vonis, diagnosis, atau bahasa yang menyiratkan penilaian medis/psikologis klinis. Jika tidak, isi dengan false.
PROMPT;
    }

    private function callGeminiApi(string $systemPrompt, array $history, ?\App\Models\User $user): array
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-3.1-flash-lite');
        $timeout = config('services.gemini.timeout', 10);

        if (empty($apiKey)) {
            Log::error('Gemini API key is not set.');
            return ['advisor_response' => self::FALLBACK_RESPONSE];
        }

        // Format history ke format Gemini API dengan sanitasi PII
        $contents = [];
        foreach ($history as $msg) {
            $sanitizedMessage = $this->sanitizePII($msg['message'], $user);
            
            $contents[] = [
                'role' => $msg['role'], // Enum kita 'user' / 'ai', Gemini menerima 'user' / 'model'
                'parts' => [['text' => $sanitizedMessage]]
            ];
            // Ubah 'ai' menjadi 'model' sesuai spec Gemini API
            if (end($contents)['role'] === 'ai') {
                $contents[count($contents)-1]['role'] = 'model';
            }
        }

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]]
            ],
            'contents' => $contents,
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'temperature' => 0.4,
            ]
        ];

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        try {
            $response = Http::timeout($timeout)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                // Parse JSON dari text
                $json = json_decode($text, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($json['advisor_response'])) {
                    $advisorResponse = $json['advisor_response'];
                    $containsClinicalClaim = $json['contains_clinical_claim'] ?? false;

                    // 6. Post-filter validasi diagnosis medis (Lapis 3)
                    if ($containsClinicalClaim === true || $this->containsDiagnosisKeywords($advisorResponse)) {
                        Log::warning('Gemini API output rejected due to medical diagnosis claim', ['text' => $advisorResponse]);
                        return ['advisor_response' => self::FALLBACK_RESPONSE];
                    }

                    return ['advisor_response' => $advisorResponse];
                }
                
                Log::warning('Gemini API returned invalid JSON format', ['text' => $text]);
            } else {
                Log::error('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
        }

        return ['advisor_response' => self::FALLBACK_RESPONSE];
    }

    private function containsDiagnosisKeywords(string $text): bool
    {
        $lowerText = strtolower($text);
        foreach (self::DIAGNOSIS_KEYWORDS as $keyword) {
            if (str_contains($lowerText, $keyword)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Best-effort PII sanitization.
     * Menggunakan regex untuk email dan str_ireplace untuk nama lengkap.
     * Ini bukan jaminan absolut karena tidak menangkap semua variasi ketik/identitas,
     * konsisten dengan pendekatan best-effort Lapis 1 & 2.
     */
    private function sanitizePII(string $text, ?\App\Models\User $user): string
    {
        // 1. Strip pola email standar
        $text = preg_replace('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', '[dihapus]', $text);
        
        // 2. Strip kemunculan nama user (case-insensitive)
        if ($user && !empty($user->name)) {
            $text = str_ireplace($user->name, '[dihapus]', $text);
        }

        return $text;
    }

    public function generateFlashcards(string $noteContent): array
    {
        $prompt = "Buatkan minimal 5 pasangan flashcard (pertanyaan dan jawaban) berdasarkan HANYA teks berikut. Jangan menambahkan informasi yang tidak ada di teks.\n\n"
            . "Output HANYA dalam format JSON array berisi object dengan key 'question' dan 'answer' tanpa markdown formatting apapun.\n\n"
            . "Teks:\n" . $noteContent;

        try {
            $apiKey = config('services.gemini.api_key');
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                // Bersihkan potensi markdown ```json ... ```
                $text = preg_replace('/```json/i', '', $text);
                $text = preg_replace('/```/', '', $text);
                
                $flashcards = json_decode(trim($text), true);
                if (is_array($flashcards)) {
                    return $flashcards;
                }
            }
            return [];
        } catch (\Exception $e) {
            Log::error('Gemini generateFlashcards error: ' . $e->getMessage());
            return [];
        }
    }

    public function generateQuiz(string $sourceContent, int $count = 5): array
    {
        $prompt = "Buatkan {$count} soal pilihan ganda (A, B, C, D) beserta jawaban benar dan pembahasan singkat berdasarkan HANYA teks berikut. Jangan menambahkan informasi yang tidak ada di teks.\n\n"
            . "Output HANYA dalam format JSON array berisi object dengan struktur: { \"question\": \"...\", \"option_a\": \"...\", \"option_b\": \"...\", \"option_c\": \"...\", \"option_d\": \"...\", \"correct_option\": \"a|b|c|d\", \"explanation\": \"...\" } tanpa markdown formatting apapun.\n\n"
            . "Teks:\n" . $sourceContent;

        try {
            $apiKey = config('services.gemini.api_key');
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                $text = preg_replace('/```json/i', '', $text);
                $text = preg_replace('/```/', '', $text);
                
                $quiz = json_decode(trim($text), true);
                if (is_array($quiz)) {
                    return $quiz;
                }
            }
            return [];
        } catch (\Exception $e) {
            Log::error('Gemini generateQuiz error: ' . $e->getMessage());
            return [];
        }
    }
}
