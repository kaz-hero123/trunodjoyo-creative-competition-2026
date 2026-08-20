<?php

namespace App\Services;

use App\Models\User;
use App\Models\Assessment;
use App\Models\Resource;
use Illuminate\Support\Collection;

class ResourceMatchingService
{
    /**
     * Memilih maksimal 5 resource yang relevan untuk user berdasarkan assessment.
     *
     * @param User $user
     * @param Assessment $assessment
     * @return Collection Collection dari objek dengan properti 'resource' dan 'reason'
     */
    public function match(User $user, Assessment $assessment): Collection
    {
        $dimensions = ['academic', 'financial', 'motivational', 'social'];
        $needsHelp = [];

        // 1. Ambil dimensi yang statusnya Berkembang atau Perlu Perhatian
        foreach ($dimensions as $dim) {
            $status = $assessment->dimensionStatus($dim);
            if ($status !== 'Kuat') {
                $scoreField = 'score_' . $dim;
                $needsHelp[] = [
                    'dimension' => $dim,
                    'status' => $status,
                    'score' => $assessment->$scoreField,
                ];
            }
        }

        // Jika semua Kuat, kembalikan collection kosong
        if (empty($needsHelp)) {
            return collect();
        }

        // Urutkan dimensi berdasarkan: 
        // 1. Perlu Perhatian lebih dulu (status = 'Perlu Perhatian' > 'Berkembang')
        // 2. Score terendah
        // 3. Urutan tetap (academic, financial, motivational, social)
        usort($needsHelp, function ($a, $b) use ($dimensions) {
            if ($a['status'] !== $b['status']) {
                return $a['status'] === 'Perlu Perhatian' ? -1 : 1;
            }
            if ($a['score'] != $b['score']) {
                return $a['score'] <=> $b['score'];
            }
            return array_search($a['dimension'], $dimensions) <=> array_search($b['dimension'], $dimensions);
        });

        // 2. Ambil resource yang aktif, belum expired, dan eligible
        $query = Resource::active()
            ->where(function ($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now()->toDateString());
            })
            ->where('min_semester', '<=', $user->semester)
            ->where('max_semester', '>=', $user->semester)
            ->where(function ($q) use ($user) {
                $q->whereNull('eligible_majors')
                  ->orWhereJsonContains('eligible_majors', $user->major);
            });

        $eligibleResources = $query->get();

        $matchedResources = collect();
        $matchedIds = [];

        $labels = [
            'academic' => 'Akademik',
            'financial' => 'Finansial',
            'motivational' => 'Motivasi',
            'social' => 'Sosial',
        ];

        // 3 & 4. Pilih resource berdasarkan prioritas dimensi (maksimal 5 total)
        foreach ($needsHelp as $need) {
            if ($matchedResources->count() >= 5) {
                break;
            }

            $dim = $need['dimension'];
            
            // Filter resource untuk dimensi ini, yang belum dipilih
            $resourcesForDim = $eligibleResources->filter(function ($resource) use ($dim, $matchedIds) {
                if (in_array($resource->id, $matchedIds)) {
                    return false;
                }
                return in_array($dim, $resource->target_dimensions ?? []);
            });

            // Urutkan berdasarkan deadline terdekat (null ditaruh di akhir)
            $sortedResources = $resourcesForDim->sort(function ($a, $b) {
                if ($a->deadline === $b->deadline) {
                    return $a->id <=> $b->id; // Deterministic tie breaker
                }
                if (is_null($a->deadline)) {
                    return 1;
                }
                if (is_null($b->deadline)) {
                    return -1;
                }
                return $a->deadline <=> $b->deadline;
            });

            foreach ($sortedResources as $resource) {
                if ($matchedResources->count() >= 5) {
                    break;
                }
                
                $reason = "Resource ini mendukung dimensi {$labels[$dim]} dan tersedia untuk semester {$user->semester}.";
                $matchedResources->push((object)[
                    'resource' => $resource,
                    'reason' => $reason
                ]);
                $matchedIds[] = $resource->id;
            }
        }

        return $matchedResources;
    }
}
