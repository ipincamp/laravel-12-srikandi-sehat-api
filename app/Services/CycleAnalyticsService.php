<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CycleAnalyticsService
{
    /**
     * Menghitung dan menganalisis riwayat siklus untuk seorang pengguna.
     */
    public function calculateForUser(User $user): array
    {
        $completedCycles = $user->menstrualCycles()
            ->whereNotNull('finish_date')
            ->orderBy('start_date', 'asc')
            ->get();

        $periodData = $this->calculatePeriodLengths($completedCycles);
        $cycleData = $this->calculateCycleLengths($completedCycles);

        return [
            'total_completed_cycles' => $completedCycles->count(),
            'average_period_length' => $periodData['average'],
            'period_length_category' => $this->getPeriodCategory($periodData['average']),
            'average_cycle_length' => $cycleData['average'],
            'cycle_length_category' => $this->getCycleCategory($cycleData['average']),
        ];
    }

    /**
     * Menghitung rata-rata durasi haid.
     */
    private function calculatePeriodLengths(Collection $cycles): array
    {
        if ($cycles->isEmpty()) {
            return ['lengths' => collect(), 'average' => null];
        }

        $lengths = $cycles->map(function ($cycle) {
            $startDate = Carbon::parse($cycle->start_date);
            $endDate = Carbon::parse($cycle->finish_date);
            return abs($endDate->diffInDays($startDate)) + 1;
        });

        return [
            'lengths' => $lengths,
            'average' => round($lengths->avg())
        ];
    }

    /**
     * Menghitung rata-rata panjang siklus.
     */
    private function calculateCycleLengths(Collection $cycles): array
    {
        if ($cycles->count() < 2) {
            return ['lengths' => collect(), 'average' => null];
        }

        $lengths = collect();
        for ($i = 0; $i < ($cycles->count() - 1); $i++) {
            $startDate = Carbon::parse($cycles[$i]->start_date);
            $nextStartDate = Carbon::parse($cycles[$i + 1]->start_date);
            $lengths->push(abs($nextStartDate->diffInDays($startDate)));
        }

        return [
            'lengths' => $lengths,
            'average' => round($lengths->avg())
        ];
    }

    /**
     * Memberikan kategori berdasarkan rata-rata lama haid.
     */
    private function getPeriodCategory(?float $averageDays): ?string
    {
        if (is_null($averageDays)) return null;

        if ($averageDays < 3) return 'Hipomenorea'; // Kurang dari 3 hari
        if ($averageDays > 7) return 'Menoragia';   // Lebih dari 7 hari
        
        return 'Normal'; // Antara 3-7 hari
    }
    
    /**
     * Memberikan kategori berdasarkan rata-rata panjang siklus.
     */
    private function getCycleCategory(?float $averageDays): ?string
    {
        if (is_null($averageDays)) return null;

        if ($averageDays < 21) return 'Polimenorea'; // Kurang dari 21 hari
        if ($averageDays > 35) return 'Oligomenorea'; // Lebih dari 35 hari
        
        return 'Normal'; // Antara 21-35 hari
    }
}