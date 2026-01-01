<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:51200', 'mimetypes:video/*'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'Ukuran video maksimal 50MB.',
            'file.mimetypes' => 'Hanya file video yang diperbolehkan.',
        ];
    }
}
