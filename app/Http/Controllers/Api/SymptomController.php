<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Symptom;
use Illuminate\Support\Facades\Cache;

class SymptomController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        // Gunakan cache agar tidak query berulang kali
        $symptoms = Cache::remember('symptoms_list_all', 86400, function () {
            // 86400 detik = 24 jam
            return Symptom::get(['name', 'category', 'description']);
        });

        return $this->json(
            message: 'Symptoms list retrieved successfully',
            data: $symptoms,
        );
    }
}
