<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddSongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('playlist'));
    }

    public function rules(): array
    {
        return [
            'song_id' => [
                'required', 'integer', 'exists:songs,id',
                Rule::unique('playlist_songs', 'song_id')
                    ->where('playlist_id', $this->route('playlist')->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'song_id.exists' => 'Lagu tidak ditemukan.',
            'song_id.unique' => 'Lagu itu sudah ada di playlist ini.',
        ];
    }
}