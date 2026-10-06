<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AlbumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $isCreate = $this->isMethod('post');

        return [
            'name'         => ['required', 'string', 'max:150'],
            'artist_name'  => ['required', 'string', 'max:100'],
            'genre_id'     => [$isCreate ? 'required' : 'nullable', 'exists:genres,id'],
            'release_date' => ['nullable', 'date'],
            'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'zip'          => [$isCreate ? 'required' : 'prohibited', 'file', 'mimes:zip', 'max:51200'],
        ];
    }
}
