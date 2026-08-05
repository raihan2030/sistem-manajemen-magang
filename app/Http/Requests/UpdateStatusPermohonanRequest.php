<?php

namespace App\Http\Requests;

use App\Models\PengajuanMagang;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusPermohonanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pengajuan = PengajuanMagang::find($this->route('id'));
        $statusSaatIni = $pengajuan?->status;

        return [
            'status' => ['required', 'in:Diterima,Ditolak,Revisi'],

            // 1. Komentar WAJIB jika status Revisi atau Ditolak
            'komentar_revisi' => [
                'required_if:status,Revisi,Ditolak',
                'nullable',
                'string',
                'max:1000'
            ],

            // 2. Surat Balasan WAJIB hanya saat PERTAMA KALI disetujui
            // (bukan saat sudah Diterima lalu cuma edit data pembimbing)
            'surat_balasan' => [
                $statusSaatIni !== 'Diterima' ? 'required_if:status,Diterima' : 'nullable',
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120'
            ],

            'nama_pembimbing' => ['nullable', 'string', 'max:150'],
            'no_wa_pembimbing' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'             => 'Status permohonan wajib dipilih.',
            'komentar_revisi.required_if' => 'Catatan verifikator wajib diisi jika status Revisi atau Ditolak.',
            'surat_balasan.required_if'   => 'Surat balasan resmi wajib diunggah saat menyetujui permohonan.',
            'surat_balasan.mimes'         => 'Surat balasan harus dokumen berformat PDF.',
            'surat_balasan.max'           => 'Ukuran berkas surat balasan tidak boleh melebihi 5 MB.',
        ];
    }
}