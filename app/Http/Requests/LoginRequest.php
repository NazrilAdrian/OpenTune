<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:100'],
            'password' => ['required'],
            'remember' => ['nullable', 'boolean'], // opsional: checkbox "Remember Me"
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Username atau email wajib diisi.',
            'login.max' => 'Username atau email maksimal 100 karakter.',
            'password.required' => 'Password wajib diisi.',
        ];
    }
}
