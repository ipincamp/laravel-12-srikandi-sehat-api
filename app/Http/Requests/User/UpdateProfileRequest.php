<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
                'sometimes',
                'required',
                'string',
                'max:255'
            ],
            // email
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
            // TODO: photo_path
            // phone
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)[0-9]{8,15}$/' // Indonesian phone number format
            ],
            // address
            'address' => [
                'sometimes',
                'nullable',
                'exists:villages,id'
            ],
            // date_of_birth
            'date_of_birth' => [
                'sometimes',
                'nullable',
                'date',
                'before_or_equal:today'
            ],
            // height_cm
            'height_cm' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0'
            ],
            // weight_kg
            'weight_kg' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0'
            ],
        ];
    }
}
