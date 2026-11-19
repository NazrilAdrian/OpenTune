<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    // Rapikan spasi di awal/akhir supaya "Rock " dan "Rock" dianggap sama
    protected function prepareForValidation(): void
    {
        $this->merge(['name' => trim((string) $this->input('name'))]);
    }

    public function rules(): array
    {
        return [
            // route('genre') = null saat create, model Genre saat update
            'name'        => ['required', 'string', 'max:50',
                              Rule::unique('genres', 'name')->ignore($this->route('genre'))],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Nama genre wajib diisi.',
            'name.max'        => 'Nama genre maksimal 50 karakter.',
            'name.unique'     => 'Genre dengan nama itu sudah ada.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}