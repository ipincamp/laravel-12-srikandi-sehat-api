<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cycle\LogSymptomRequest;
use App\Http\Resources\Cycle\FinishedCycleResource;
use App\Models\Symptom;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MenstrualCycleController extends Controller
{
    /**
     * Mencatat tanggal mulai menstruasi.
     */
    public function start(Request $request)
    {
        $user = $request->user();

        // Cek apakah sudah ada siklus yang aktif (belum ada finish_date)
        $activeCycleExists = $user->menstrualCycles()->whereNull('finish_date')->exists();

        if ($activeCycleExists) {
            return $this->json(
                status: 409,
                message: 'A new cycle cannot be started while another is still active.',
            );
        }

        // Buat siklus baru
        $user->menstrualCycles()->create([
            'start_date' => now(),
        ]);

        return $this->json(
            status: 201,
            message: 'Menstrual cycle started successfully.',
        );
    }

    /**
     * Mencatat tanggal selesai menstruasi.
     */
    public function finish(Request $request)
    {
        $user = $request->user();

        // Cari siklus yang sedang aktif
        $activeCycle = $user->menstrualCycles()->whereNull('finish_date')->latest('start_date')->first();

        if (!$activeCycle) {
            return $this->json(
                status: 404,
                message: 'No active menstrual cycle found to finish.',
            );
        }

        // Update tanggal selesai
        $activeCycle->update([
            'finish_date' => now(),
        ]);

        return $this->json(
            message: 'Menstrual cycle finished successfully.',
            data: new FinishedCycleResource($activeCycle),
        );
    }

    /**
     * Mencatat satu atau lebih gejala pada tanggal tertentu.
     */
    public function logSymptoms(LogSymptomRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        // Cari ID dari nama-nama gejala yang dikirim
        $symptomIds = Symptom::whereIn('name', $validated['symptoms'])->pluck('id');

        // (Opsional) Cari siklus yang sedang aktif untuk dihubungkan
        $activeCycle = $user->menstrualCycles()->whereNull('finish_date')->latest('start_date')->first();

        // Buat entri catatan gejala
        $entry = $user->symptomEntries()->create([
            'log_date'           => $validated['log_date'],
            'notes'              => $validated['notes'] ?? null,
            'menstrual_cycle_id' => $activeCycle?->id,
        ]);

        // Hubungkan entri dengan semua gejala yang dipilih
        $entry->symptoms()->sync($symptomIds);

        // Kembalikan data yang baru dibuat beserta relasinya
        $entry->load('symptoms:id,name');

        return $this->json(
            status: 201,
            message: 'Symptoms logged successfully.',
            data: $entry,
        );
    }

    /**
     * Mengambil riwayat siklus menstruasi yang sudah selesai.
     */
    public function history(Request $request)
    {
        $user = $request->user();

        // 1. Ambil semua siklus yang sudah selesai (finish_date tidak null)
        $completedCycles = $user->menstrualCycles()
            ->whereNotNull('finish_date')
            ->orderBy('start_date', 'asc')
            ->get();

        // Jika data kurang dari 1, tidak ada yang bisa dihitung
        if ($completedCycles->count() < 1) {
            return $this->json(
                message: 'No completed cycle history found.',
            );
        }

        $history = [];
        // 2. Lakukan perulangan untuk menghitung durasi
        foreach ($completedCycles as $index => $currentCycle) {
            // Konversi ke objek Carbon untuk perhitungan
            $startDate = Carbon::parse($currentCycle->start_date);
            $endDate = Carbon::parse($currentCycle->finish_date);

            // Hitung Lama Haid (Period Length)
            // Selisih hari antara selesai dan mulai, ditambah 1
            $periodLength = $endDate->diffInDays($startDate) + 1;

            $cycleLength = null;
            // Hitung Panjang Siklus (Cycle Length) jika ada siklus berikutnya
            if (isset($completedCycles[$index + 1])) {
                $nextCycleStartDate = Carbon::parse($completedCycles[$index + 1]->start_date);
                // Selisih hari dari mulai haid ini ke mulai haid berikutnya
                $cycleLength = $nextCycleStartDate->diffInDays($startDate);
            }

            $history[] = [
                'start_date' => $currentCycle->start_date,
                'finish_date' => $currentCycle->finish_date,
                // Lama dia haid dalam hari
                'period_length_days' => $periodLength,
                // Jarak dari mulai haid ini ke haid berikutnya.
                // Nilainya null untuk siklus terakhir karena belum ada data haid selanjutnya.
                'cycle_length_days' => $cycleLength,
            ];
        }

        return $this->json(
            message: 'Cycle history retrieved successfully',
            data: array_reverse($history),
        );
    }
}
