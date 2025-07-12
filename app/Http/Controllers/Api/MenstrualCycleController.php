<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cycle\LogSymptomRequest;
use App\Http\Resources\Cycle\CycleHistoryResource;
use App\Http\Resources\Cycle\FinishedCycleResource;
use App\Http\Resources\Cycle\SymptomEntryResource;
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
            'log_date'           => Carbon::parse($validated['log_date']),
            'notes'              => $validated['notes'] ?? null,
            'menstrual_cycle_id' => $activeCycle?->id,
        ]);

        // Hubungkan entri dengan semua gejala yang dipilih
        $entry->symptoms()->sync($symptomIds);

        // Kembalikan data yang baru dibuat beserta relasinya
        $entry->load('symptoms');

        return $this->json(
            status: 201,
            message: 'Symptoms logged successfully.',
            data: new SymptomEntryResource($entry),
        );
    }

    /**
     * Mengambil riwayat siklus menstruasi yang sudah selesai.
     */
    public function history(Request $request)
    {
        $user = $request->user();

        // 1. Ambil siklus yang sudah selesai DAN muat relasi gejalanya (Eager Loading)
        $completedCycles = $user->menstrualCycles()
            ->whereNotNull('finish_date')
            ->with('symptomEntries.symptoms')
            ->orderBy('start_date', 'asc')
            ->get();

        if ($completedCycles->isEmpty()) {
            return $this->json(
                status: 404,
                message: 'No completed cycle history found.',
            );
        }

        // Gunakan map untuk memproses koleksi dan menambahkan data kalkulasi
        $historyData = $completedCycles->map(function ($currentCycle, $index) use ($completedCycles) {
            $startDate = Carbon::parse($currentCycle->start_date);
            $endDate = Carbon::parse($currentCycle->finish_date);

            $periodLength = $endDate->diffInDays($startDate) + 1;
            $cycleLength = null;

            if (isset($completedCycles[$index + 1])) {
                $nextCycleStartDate = Carbon::parse($completedCycles[$index + 1]->start_date);
                $cycleLength = $nextCycleStartDate->diffInDays($startDate);
            }

            // Kembalikan sebagai objek yang berisi model asli dan data kalkulasi
            return (object) [
                'cycle' => $currentCycle,
                'period_length_days' => abs(round($periodLength)),
                'cycle_length_days' => abs(round($cycleLength)),
            ];
        });

        return $this->json(
            message: 'Cycle history retrieved successfully',
            data: CycleHistoryResource::collection($historyData->reverse()),
        );
    }
}
