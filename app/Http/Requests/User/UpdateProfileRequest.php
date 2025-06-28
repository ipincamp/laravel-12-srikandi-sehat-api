<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // name
            'name' => [
                'string',
                'max:255',
            ],
            // email
            'email' => [
                'string',
                'email',
                'max:255',
                'unique:users,email,' . Auth::id(),
            ],
            // TODO: photo_path
            // phone
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)[0-9]{8,15}$/', // Indonesian phone number format
            ],
            // address
            'address' => [
                'nullable',
                'exists:villages,id',
            ],
            // date_of_birth
            'date_of_birth' => [
                'nullable',
                'date',
                'before:today',
            ],
            // height_cm
            'height_cm' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            // weight_kg
            'weight_kg' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }
}
