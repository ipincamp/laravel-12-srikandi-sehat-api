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
        // Ambil 2 siklus terakhir yang sudah selesai untuk analisis
        $lastTwoCompletedCycles = $user->menstrualCycles()
            ->whereNotNull('finish_date')
            ->latest('start_date')
            ->take(2)
            ->get();
        
        $activeCycle = $user->activeCycle;
        $lastCompletedCycle = $lastTwoCompletedCycles->first();

        // Siapkan struktur respons dengan semua flag
        $summary = [
            'active_cycle_running_days' => null,
            'notification_flags' => [
                'period_is_prolonged' => false, // Haid saat ini > 7 hari
                'period_is_short' => false,     // Haid terakhir < 3 hari
                'cycle_is_late' => false,       // Siklus berikutnya > 35 hari belum mulai
                'cycle_is_short' => false,      // Siklus terakhir < 21 hari
            ]
        ];

        // 1. Analisis jika ada siklus yang SEDANG BERJALAN
        if ($activeCycle) {
            $runningDays = abs(Carbon::now()->diffInDays(Carbon::parse($activeCycle->start_date))) + 1;
            $summary['active_cycle_running_days'] = $runningDays;

            // Cek apakah haid terlalu lama & notifikasi belum dikirim
            if ($runningDays > 7 && is_null($activeCycle->period_prolonged_notified_at)) {
                $summary['notification_flags']['period_is_prolonged'] = true;
            }
        }
        // 2. Analisis jika TIDAK ada siklus aktif (cek keterlambatan siklus berikutnya)
        elseif ($lastCompletedCycle) {
            $daysSinceLastCycleEnded = abs(Carbon::now()->diffInDays(Carbon::parse($lastCompletedCycle->finish_date)));

            // Cek apakah siklus terlambat & notifikasi belum dikirim
            if ($daysSinceLastCycleEnded > 35 && is_null($lastCompletedCycle->cycle_irregularity_notified_at)) {
                $summary['notification_flags']['cycle_is_late'] = true;
            }
        }

        // 3. Analisis SIKLUS TERAKHIR YANG SUDAH SELESAI
        if ($lastCompletedCycle) {
            // Cek durasi haid terakhir terlalu pendek
            $lastPeriodLength = abs(Carbon::parse($lastCompletedCycle->finish_date)->diffInDays(Carbon::parse($lastCompletedCycle->start_date))) + 1;
            if ($lastPeriodLength < 3 && is_null($lastCompletedCycle->period_prolonged_notified_at)) {
                $summary['notification_flags']['period_is_short'] = true;
            }
            
            // Cek panjang siklus terakhir terlalu pendek (butuh 2 data siklus)
            $previousCycle = $lastTwoCompletedCycles->last();
            if ($lastTwoCompletedCycles->count() > 1) {
                $lastCycleLength = abs(Carbon::parse($lastCompletedCycle->start_date)->diffInDays(Carbon::parse($previousCycle->start_date)));
                if ($lastCycleLength < 21 && is_null($lastCompletedCycle->cycle_irregularity_notified_at)) {
                    $summary['notification_flags']['cycle_is_short'] = true;
                }
            }
        }
        
        return $summary;
    }
    
    public function calculateForUserss(User $user): array
    {
        // Ambil siklus terakhir (baik yang sedang aktif maupun yang baru selesai)
        $lastCycle = $user->menstrualCycles()->latest('start_date')->first();
        $activeCycle = $user->activeCycle; // Relasi yang sudah kita buat sebelumnya

        // $completedCycles = $user->menstrualCycles()
        //     ->whereNotNull('finish_date')
        //     ->orderBy('start_date', 'asc')
        //     ->get();

        // $periodData = $this->calculatePeriodLengths($completedCycles);
        // $cycleData = $this->calculateCycleLengths($completedCycles);

        $summary = [
            // 'total_completed_cycles' => $completedCycles->count(),
            // 'average_period_length' => $periodData['average'],
            // 'period_length_category' => $this->getPeriodCategory($periodData['average']),
            // 'average_cycle_length' => $cycleData['average'],
            // 'cycle_length_category' => $this->getCycleCategory($cycleData['average']),
            'active_cycle_running_days' => null,
            'notification_flags' => [
                'period_is_prolonged' => false, // Haid saat ini > 7 hari
                'cycle_is_late' => false,       // Siklus berikutnya > 35 hari
            ]
        ];

        // 1. Analisis jika ada siklus yang sedang berjalan
        if ($activeCycle) {
            $runningDays = abs(Carbon::now()->diffInDays(Carbon::parse($activeCycle->start_date))) + 1;
            $summary['active_cycle_running_days'] = $runningDays;

            // Cek apakah haid terlalu lama & notifikasi belum dikirim
            if ($runningDays > 7 && is_null($activeCycle->period_prolonged_notified_at)) {
                $summary['notification_flags']['period_is_prolonged'] = true;
                // Di sini Anda bisa menambahkan logika untuk menandai bahwa notifikasi sudah dikirim
                // $activeCycle->update(['period_prolonged_notified_at' => now()]);
            }
        }
        // 2. Analisis jika tidak ada siklus aktif (cek keterlambatan)
        elseif ($lastCycle) {
            $daysSinceLastCycleEnded = abs(Carbon::now()->diffInDays(Carbon::parse($lastCycle->finish_date)));

            // Cek apakah siklus terlambat & notifikasi belum dikirim
            if ($daysSinceLastCycleEnded > 35 && is_null($lastCycle->cycle_irregularity_notified_at)) {
                $summary['notification_flags']['cycle_is_late'] = true;
                // Di sini Anda bisa menambahkan logika untuk menandai bahwa notifikasi sudah dikirim
                // $lastCycle->update(['cycle_irregularity_notified_at' => now()]);
            }
        }

        return $summary;
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
