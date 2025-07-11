<?php

namespace App\Http\Requests\Cycle;

use Illuminate\Foundation\Http\FormRequest;

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
                'exists:symptoms,name',
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
