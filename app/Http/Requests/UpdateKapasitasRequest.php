<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKapasitasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_bidang' => ['required', 'string', 'max:255'],
            'kuota_total' => ['required', 'integer', 'min:0', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'kuota_total.min' => 'Total kuota tidak boleh bernilai negatif.',
            'kuota_total.max' => 'Total kuota tidak boleh lebih dari 50.',
        ];
    }
}