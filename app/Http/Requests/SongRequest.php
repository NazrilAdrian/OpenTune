<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SongRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool { return true; }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isCreate = $this->isMethod('post');

        return [
            'title'       => ['required', 'string', 'max:150'],
            'artist_name' => ['required', 'string', 'max:100'],
            'genre_id'    => ['required', 'exists:genres,id'],
            'album_id'    => ['nullable', 'exists:albums,id'],
            'audio'       => [$isCreate ? 'required' : 'nullable', 'file', 'mimes:mp3,m4a', 'max:51200'], // 50 MB
            'cover'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],                 // 5 MB
        ];
    }

    public function messages(): array
    {
        return [
            'audio.required' => 'File audio wajib dipilih.',
            'audio.mimes'    => 'File audio harus berformat MP3 atau M4A.',
            'audio.max'      => 'Ukuran file audio maksimal 50 MB.',
            'cover.max'      => 'Ukuran cover maksimal 5 MB.',
        ];
    }
}
