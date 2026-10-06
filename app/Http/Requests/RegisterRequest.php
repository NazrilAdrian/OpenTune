<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // regex melarang spasi & '@' agar username tidak tertukar dengan email saat login
            'username' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9_.-]+$/', 'unique:users,username'],
            'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'terms'    => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex'    => 'Username hanya boleh berisi huruf, angka, titik, garis bawah, dan strip.',
            'username.unique'   => 'Username sudah dipakai.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted'    => 'Kamu harus menyetujui Terms of Service dan Privacy Policy.',
        ];
    }
}