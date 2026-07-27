<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusPermohonanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:Diterima,Ditolak,Revisi'],
            'komentar_revisi' => ['nullable', 'string'],
        ];
    }
}