<?php

namespace App\Http\Requests;

use App\Models\PengajuanMagang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePengajuanMagangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $minTanggalMulai = 'today';

        if ($isUpdate) {
            $pengajuan = $this->route('pengajuan_magang')
                ?? $this->route('pengajuan')
                ?? $this->route('id');

            if ($pengajuan && ! $pengajuan instanceof PengajuanMagang) {
                $pengajuan = PengajuanMagang::find($pengajuan);
            }

            if ($pengajuan && $pengajuan->tanggal_pengajuan) {
                $minTanggalMulai = \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->toDateString();
            }
        }

        return [
            'bidang_id' => ['required', 'integer', 'exists:bidang,id'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:' . $minTanggalMulai],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'surat_permohonan' => $isUpdate
                ? ['nullable', 'file', 'mimes:pdf', 'max:5120']
                : ['required', 'file', 'mimes:pdf', 'max:5120'],
            'anggota' => ['required', 'array', 'min:1', 'max:5'],
            'anggota.*.nama_lengkap' => ['required', 'string', 'max:60'],
            'anggota.*.nim_nisn' => ['required', 'string', 'min:8', 'max:13'],
            'anggota.*.kartu_identitas' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'anggota.*.jurusan_prodi'    => ['required', 'string', 'max:100'],
            'jenjang_pendidikan' => ['required', 'in:SMA/SMK/Sederajat,Perguruan Tinggi'],
            'institusi_asal'     => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'bidang_id.required' => 'Silakan pilih bidang instansi tujuan magang.',
            'bidang_id.exists' => 'Bidang yang dipilih tidak valid.',

            'tanggal_mulai.required' => 'Tanggal mulai magang tidak boleh kosong.',
            'tanggal_mulai.after_or_equal' => $this->isMethod('put') || $this->isMethod('patch')
                ? 'Tanggal mulai tidak boleh sebelum tanggal pengajuan awal.'
                : 'Tanggal mulai hanya bisa dipilih dari hari ini dan seterusnya.',

            'tanggal_selesai.required' => 'Tanggal selesai magang tidak boleh kosong.',
            'tanggal_selesai.after' => 'Tanggal selesai harus diset minimal sehari setelah tanggal mulai.',

            'surat_permohonan.required' => 'Surat pengantar wajib diunggah.',
            'surat_permohonan.mimes' => 'Surat pengantar wajib berformat PDF.',
            'surat_permohonan.max' => 'Ukuran file surat pengantar maksimal 5 MB.',

            'anggota.required' => 'Data pemohon/anggota magang wajib diisi.',
            'anggota.min' => 'Pendaftaran minimal menyertakan 1 pemohon.',
            'anggota.max' => 'Pendaftaran kelompok maksimal terdiri dari 5 anggota.',

            'anggota.*.nama_lengkap.required' => 'Nama lengkap pemohon/anggota tidak boleh kosong.',

            'anggota.*.nim_nisn.required' => 'NISN/NIM tidak boleh kosong.',
            'anggota.*.nim_nisn.min' => 'NISN/NIM minimal terdiri dari 8 karakter.',
            'anggota.*.nim_nisn.max' => 'NISN/NIM maksimal terdiri dari 13 karakter.',

            'anggota.*.kartu_identitas.mimes' => 'KTM/Kartu Pelajar wajib berformat PDF.',
            'anggota.*.kartu_identitas.max' => 'Ukuran file KTM/Kartu Pelajar maksimal 5 MB.',
        ];
    }
}
