<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $target = $this->route('user'); // null saat create, model User saat update

        return [
            // aturan username sama dengan RegisterRequest (Nazril)
            'username' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9_.-]+$/',
                           Rule::unique('users', 'username')->ignore($target)],
            'email'    => ['required', 'email', 'max:100',
                           Rule::unique('users', 'email')->ignore($target)],
            // saat edit akun sendiri, select role dinonaktifkan sehingga tidak dikirim
            'role'     => [$target ? 'nullable' : 'required', Rule::in(['user', 'admin'])],
            // create: wajib. edit: kosong = password tidak diubah
            'password' => [$target ? 'nullable' : 'required', 'confirmed',
                           Password::min(8)->mixedCase()->numbers()],
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex'     => 'Username hanya boleh berisi huruf, angka, titik, garis bawah, dan strip.',
            'username.unique'    => 'Username sudah dipakai.',
            'email.unique'       => 'Email sudah terdaftar.',
            'role.required'      => 'Role wajib dipilih.',
            'role.in'            => 'Role harus user atau admin.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}