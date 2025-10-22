<?php

namespace App\Http\Resources\Cycle;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SymptomEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $recommendations = [];
        $loggedSymptoms = [];

        // Lakukan perulangan pada relasi gejala yang sudah di-load
        if ($this->relationLoaded('symptoms')) {
            foreach ($this->symptoms as $symptom) {
                // 1. Buat array untuk bagian "Penanganan"
                // Hanya tambahkan jika ada teks rekomendasi
                if (!empty($symptom->recommendation_txt)) {
                    $recommendationUrls = [];
                    if (!empty($symptom->recommendation_url)) {
                        $recommendationUrls = array_map(function ($url) {
                            return [
                                'id' => $url['id'] ?? null,
                                'action' => $url['action'] ?? null,
                                'video_url' => $url['video_url'] ?? null,
                            ];
                        }, json_decode(str_replace("'", '"', $symptom->recommendation_url), true) ?: []);
                    }
                    $recommendations[] = [
                        'symptom_name' => $symptom->name,
                        'recommendation_txt' => $symptom->recommendation_txt,
                        'recommendation_url' => $recommendationUrls,
                    ];
                }

                // 2. Buat array sederhana untuk bagian "Gejala yang Dialami"
                $loggedSymptoms[] = $symptom->name;
            }
        }

        return [
            'id' => $this->id,
            'log_date' => Carbon::parse($this->log_date)->toDateString(),
            'mood_score' => $this->mood_score,
            'notes' => $this->notes,
            'recommendations' => $recommendations, // <-- Data untuk "Penanganan"
            'logged_symptoms' => $loggedSymptoms,   // <-- Data untuk "Gejala yang Dialami"
        ];
    }
}
