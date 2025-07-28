<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cycle\LogSymptomRequest;
use App\Http\Resources\Cycle\AllSymptomResource;
use App\Http\Resources\Cycle\CycleDetailResource;
use App\Http\Resources\Cycle\CycleHistoryResource;
use App\Http\Resources\Cycle\FinishedCycleResource;
use App\Http\Resources\Cycle\SymptomEntryResource;
use App\Models\MenstrualCycle;
use App\Models\Symptom;
use App\Models\SymptomEntry;
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
     * Mengambil detail siklus menstruasi yang sudah selesai berdasarkan ID.
     * Hanya bisa diakses jika siklus sudah selesai (finish_date tidak null).
     */
    public function show(Request $request, string $cycleId)
    {
        // Cari siklus berdasarkan ID
        $cycle = MenstrualCycle::find($cycleId);

        if (!$cycle) {
            return $this->json(
                status: 404,
                message: 'Menstrual cycle not found.',
            );
        }

        // 1. Cek Otorisasi: Boleh diakses jika...
        //    a) Pengguna adalah 'admin', ATAU
        //    b) ID pengguna sama dengan ID pemilik siklus
        if (!$request->user()->hasRole('admin') && $request->user()->id !== $cycle->user_id) {
            return $this->json(
                message: 'This action is unauthorized.',
                status: 403,
            );
        }

        // 2. Cek apakah siklus sudah selesai (sesuai permintaan Anda sebelumnya)
        if (is_null($cycle->finish_date)) {
            return $this->json(
                message: 'This cycle is still active and cannot be viewed in detail yet.',
                status: 404,
            );
        }

        // Muat relasi yang dibutuhkan
        $cycle->load('symptomEntries.symptoms');

        // Kembalikan data menggunakan resource
        return $this->json(
            message: 'Cycle detail retrieved successfully.',
            data: new CycleDetailResource($cycle)
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
            'mood_score'         => $validated['mood_score'] ?? null,
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
     * Mengambil riwayat gejala yang sudah dicatat.
     */
    public function symptomHistory(Request $request)
    {
        $user = $request->user();

        // Ambil semua entri gejala yang sudah dicatat
        $symptomEntries = $user->symptomEntries()
            ->with('symptoms')
            ->orderBy('log_date', 'desc')
            ->get();

        if ($symptomEntries->isEmpty()) {
            return $this->json(
                status: 404,
                message: 'No symptom history found.',
            );
        }

        // Kembalikan data dengan resource untuk konsistensi
        return $this->json(
            message: 'Symptom history retrieved successfully.',
            data: AllSymptomResource::collection($symptomEntries),
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

    /**
     * Menampilkan detail log gejala berdasarkan ID.
     */
    public function showSymptomLog(Request $request, string $logId)
    {
        $symptomEntry = SymptomEntry::find($logId);

        if (!$symptomEntry) {
            return $this->json(
                status: 404,
                message: 'Symptom log not found.',
            );
        }

        // Pastikan user yang meminta adalah pemilik data
        if ($request->user()->id !== $symptomEntry->user_id) {
            return $this->json(
                status: 403,
                message: 'This action is unauthorized.',
            );
        }

        // Muat relasi yang dibutuhkan oleh resource
        $symptomEntry->load('symptoms');

        // Gunakan resource yang sudah ada untuk konsistensi
        return $this->json(
            message: 'Symptom log retrieved successfully.',
            data: new SymptomEntryResource($symptomEntry),
        );
    }

    /**
     * Mengambil status siklus menstruasi saat ini.
     * Polimenorea < 21 hari, Oligomenorea > 35 hari, Normal 21-35 hari.
     * Juga mengambil lama haid (jumlah hari sejak mulai hingga hari ini).
     */
    public function status(Request $request)
    {
        $user = $request->user();

        // Ambil siklus menstruasi terakhir yang belum selesai
        $currentCycle = $user->menstrualCycles()
            ->whereNull('finish_date')
            ->orderBy('start_date', 'desc')
            ->first();

        if (!$currentCycle) {
            return $this->json(
                status: 404,
                message: 'No current menstrual cycle found.',
                data: [
                    'cycle_id' => null,
                    'cycle_status' => null,
                    'cycle_duration_days' => 0,
                    'period_status' => null,
                    'period_length_days' => 0,
                ]
            );
        }

        // Hitung durasi siklus saat ini
        $cycleDuration = Carbon::now()->diffInDays(Carbon::parse($currentCycle->start_date));

        // Lama haid = jumlah hari sejak start_date hingga hari ini (belum selesai)
        $periodLength = $cycleDuration + 1;

        // Tentukan status siklus
        $status = match (true) {
            $cycleDuration < 21 => 'Polimenorea',
            $cycleDuration > 35 => 'Oligomenorea',
            default => 'Normal',
        };

        return $this->json(
            message: 'Menstrual cycle status retrieved successfully.',
            data: [
                'cycle_id' => $currentCycle->id,
                'cycle_status' => $status,
                'cycle_duration_days' => abs(round($cycleDuration)),
                'period_status' => match (true) {
                    $periodLength < 3 => 'Pendek',
                    $periodLength > 7 => 'Panjang',
                    default => 'Normal',
                },
                'period_length_days' => abs(round($periodLength)),
            ]
        );
    }
}
