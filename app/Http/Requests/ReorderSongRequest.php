<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReorderSongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('playlist'));
    }

    public function rules(): array
    {
        return [
            'song_id'   => [
                'required', 'integer',
                Rule::exists('playlist_songs', 'song_id')
                    ->where('playlist_id', $this->route('playlist')->id),
            ],
            'direction' => ['required', 'in:up,down'],
        ];
    }
}