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
                'required',
                'exists:villages,code'
            ],
            // date_of_birth
            'birthdate' => [
                'sometimes',
                'nullable',
                'date',
                'before_or_equal:today'
            ],
            // tinggi badan dalam meter
            'tb_m' => [
                'sometimes',
                'nullable',
                'numeric',
                'between:0.1,3.0'
            ],
            // berat badan dalam kilogram
            'bb_kg' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0'
            ],
            // education
            'edu_now' => [
                'sometimes',
                'nullable',
                'string',
                'max:255'
            ],
            // parent education
            'edu_parent' => [
                'sometimes',
                'nullable',
                'string',
                'max:255'
            ],
            // internet access (wifi / seluler)
            'inet_access' => [
                'sometimes',
                'nullable',
                'string',
                'in:wifi,seluler'
            ],
            // first haid
            'first_haid' => [
                'sometimes',
                'nullable',
                'date',
                'before_or_equal:today'
            ],
        ];
    }
}
