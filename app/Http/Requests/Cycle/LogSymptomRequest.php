<?php

namespace App\Http\Requests\Cycle;

use App\Models\Symptom;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class LogSymptomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // 1. Ambil daftar gejala yang valid dari database dan simpan di cache
        $allowedSymptoms = Cache::remember('symptoms_list', 86400, function () {
            // 86400 detik = 24 jam
            return Symptom::pluck('name')->all();
        });

        return [
            // 'symptoms' harus berupa array dan tidak boleh kosong
            'symptoms' => [
                'required',
                'array',
                'min:1',
            ],
            // Setiap item di dalam array 'symptoms' harus ada di tabel 'symptoms' pada kolom 'name'
            'symptoms.*' => [
                'required',
                'string',
                Rule::in($allowedSymptoms),
            ],
            'notes' => [
                'nullable',
                'string',
            ],
            'log_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
        ];
    }
}
