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

        // === Tentukan batas minimal tanggal_mulai ===
        // - Create (Daftar baru): minimal hari ini
        // - Update (Edit): minimal tanggal_pengajuan milik record yang sedang diedit
        $minTanggalMulai = 'today';

        if ($isUpdate) {
            // Coba ambil record dari route parameter.
            // Sesuaikan nama parameter route jika berbeda dari yang dicoba di bawah ini.
            $pengajuan = $this->route('pengajuan_magang')
                ?? $this->route('pengajuan')
                ?? $this->route('id');

            // Jika route binding hanya berupa ID mentah (bukan instance Model), ambil manual dari DB
            if ($pengajuan && ! $pengajuan instanceof PengajuanMagang) {
                $pengajuan = PengajuanMagang::find($pengajuan);
            }

            if ($pengajuan && $pengajuan->tanggal_pengajuan) {
                $minTanggalMulai = \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->toDateString();
            }
        }

        return [
            // 1. Bidang SKPD Tujuan
            'bidang_id' => ['required', 'integer', 'exists:bidang,id'],

            // 4. Tanggal Mulai: Wajib, minimal tergantung mode (create -> hari ini, update -> tanggal_pengajuan)
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:' . $minTanggalMulai],

            // 5. Tanggal Selesai: Wajib, minimal sehari setelah tanggal mulai
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],

            // 6. Surat Pengantar: Wajib, PDF, Maksimal 5 MB (5120 KB)
            'surat_permohonan'          => $isUpdate 
                                            ? ['nullable', 'file', 'mimes:pdf', 'max:5120'] 
                                            : ['required', 'file', 'mimes:pdf', 'max:5120'],

            // Validasi Array Anggota (Min 1, Maks 5)
            'anggota' => ['required', 'array', 'min:1', 'max:5'],

            // 1. Nama Lengkap: Wajib
            'anggota.*.nama_lengkap' => ['required', 'string', 'max:255'],

            // 2. NISN/NIM: Wajib, Min 8 Karakter, Maks 13 Karakter
            'anggota.*.nim_nisn' => ['required', 'string', 'min:8', 'max:13'],

            // 3. Upload KTM: Wajib, PDF, Maksimal 5 MB (5120 KB)
            'anggota.*.kartu_identitas' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],

            'anggota.*.jurusan_prodi'    => ['required', 'string', 'max:100'],
            
            'jenjang_pendidikan' => ['required', 'in:SMA/SMK/Sederajat,Perguruan Tinggi'],
            'institusi_asal'     => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'bidang_id.required' => 'Silakan pilih bidang instansi tujuan magang.',
            'bidang_id.exists' => 'Bidang yang dipilih tidak valid.',

            // Pesan Validasi Tanggal
            'tanggal_mulai.required' => 'Tanggal mulai magang tidak boleh kosong.',
            'tanggal_mulai.after_or_equal' => $this->isMethod('put') || $this->isMethod('patch')
                ? 'Tanggal mulai tidak boleh sebelum tanggal pengajuan awal.'
                : 'Tanggal mulai hanya bisa dipilih dari hari ini dan seterusnya.',

            'tanggal_selesai.required' => 'Tanggal selesai magang tidak boleh kosong.',
            'tanggal_selesai.after' => 'Tanggal selesai harus diset minimal sehari setelah tanggal mulai.',

            // Pesan Validasi Surat Pengantar
            'surat_permohonan.required' => 'Surat pengantar wajib diunggah.',
            'surat_permohonan.mimes' => 'Surat pengantar wajib berformat PDF.',
            'surat_permohonan.max' => 'Ukuran file surat pengantar maksimal 5 MB.',

            // Pesan Validasi Anggota
            'anggota.required' => 'Data pemohon/anggota magang wajib diisi.',
            'anggota.min' => 'Pendaftaran minimal menyertakan 1 pemohon.',
            'anggota.max' => 'Pendaftaran kelompok maksimal terdiri dari 5 anggota.',

            'anggota.*.nama_lengkap.required' => 'Nama lengkap pemohon/anggota tidak boleh kosong.',

            'anggota.*.nim_nisn.required' => 'NISN/NIM tidak boleh kosong.',
            'anggota.*.nim_nisn.min' => 'NISN/NIM minimal terdiri dari 8 karakter.',
            'anggota.*.nim_nisn.max' => 'NISN/NIM maksimal terdiri dari 13 karakter.',

            'anggota.*.kartu_identitas.required' => 'Upload KTM/Kartu Pelajar tidak boleh kosong.',
            'anggota.*.kartu_identitas.mimes' => 'KTM/Kartu Pelajar wajib berformat PDF.',
            'anggota.*.kartu_identitas.max' => 'Ukuran file KTM/Kartu Pelajar maksimal 5 MB.',
        ];
    }
}