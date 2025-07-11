<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cycle\LogSymptomRequest;
use App\Models\Symptom;
use Illuminate\Http\Request;

class MenstrualCycleController extends Controller
{
    /**
     * Mencatat tanggal mulai menstruasi.
     */
    public function start(Request $request)
    {
        $user = $request->user();

        // Cek apakah sudah ada siklus yang aktif (belum ada end_date)
        $activeCycleExists = $user->menstrualCycles()->whereNull('end_date')->exists();

        if ($activeCycleExists) {
            return $this->json(
                status: 409,
                message: 'A new cycle cannot be started while another is still active.',
            );
        }

        // Buat siklus baru
        $cycle = $user->menstrualCycles()->create([
            'start_date' => now(),
        ]);

        return $this->json(
            status: 201,
            message: 'Menstrual cycle started successfully.',
            data: $cycle,
        );
    }

    /**
     * Mencatat tanggal selesai menstruasi.
     */
    public function finish(Request $request)
    {
        $user = $request->user();

        // Cari siklus yang sedang aktif
        $activeCycle = $user->menstrualCycles()->whereNull('end_date')->latest('start_date')->first();

        if (!$activeCycle) {
            return $this->json(
                status: 404,
                message: 'No active menstrual cycle found to finish.',
            );
        }

        // Update tanggal selesai
        $activeCycle->update([
            'end_date' => now(),
        ]);

        return $this->json(
            message: 'Menstrual cycle finished successfully.',
            data: $activeCycle,
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
        $activeCycle = $user->menstrualCycles()->whereNull('end_date')->latest('start_date')->first();

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
}
