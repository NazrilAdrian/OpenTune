<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; } // route sudah dilindungi middleware 'auth'

    public function rules(): array
    {
        $id = $this->user()->id;

        return [
            'username' => ['required', 'string', 'max:50',
                           Rule::unique('users', 'username')->ignore($id)],
            'email'    => ['required', 'email',
                           Rule::unique('users', 'email')->ignore($id)],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // 2 MB
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique'         => 'Username sudah dipakai.',
            'email.unique'            => 'Email sudah terdaftar.',
            'profile_picture.image'   => 'File harus berupa gambar.',
            'profile_picture.mimes'   => 'Format foto harus JPG atau PNG.',
            'profile_picture.max'     => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}