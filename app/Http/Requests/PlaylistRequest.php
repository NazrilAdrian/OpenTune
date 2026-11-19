<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaylistRequest extends FormRequest
{
    public function authorize(): bool
    {
        // store: route belum punya {playlist} -> boleh. update: harus pemilik.
        $playlist = $this->route('playlist');

        return $playlist ? $this->user()->can('update', $playlist) : true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:150'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2 MB
            'remove_cover' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama playlist wajib diisi.',
            'name.max'          => 'Nama playlist maksimal 150 karakter.',
            'description.max'   => 'Deskripsi maksimal 1000 karakter.',
            'cover.image'       => 'Cover harus berupa gambar.',
            'cover.mimes'       => 'Format cover harus JPG, PNG, atau WEBP.',
            'cover.max'         => 'Ukuran cover maksimal 2 MB.',
        ];
    }
}