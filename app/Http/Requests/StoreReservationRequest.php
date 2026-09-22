<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'facility_id' => ['required', 'exists:facilities,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'purpose' => ['required', 'string', 'max:500'],
            'proposal_kegiatan' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'proposal_permohonan' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'facility_id.required' => 'Fasilitas harus dipilih.',
            'facility_id.exists' => 'Fasilitas tidak valid.',
            'date.required' => 'Tanggal reservasi harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'date.after_or_equal' => 'Tanggal reservasi tidak boleh di masa lalu.',
            'start_time.required' => 'Waktu mulai harus diisi.',
            'start_time.date_format' => 'Format waktu mulai tidak valid (HH:MM).',
            'end_time.required' => 'Waktu selesai harus diisi.',
            'end_time.date_format' => 'Format waktu selesai tidak valid (HH:MM).',
            'end_time.after' => 'Waktu selesai harus lebih besar dari waktu mulai.',
            'purpose.required' => 'Tujuan peminjaman harus diisi.',
            'purpose.string' => 'Tujuan peminjaman harus berupa teks.',
            'purpose.max' => 'Tujuan peminjaman maksimal 500 karakter.',
            'proposal_kegiatan.required' => 'Proposal kegiatan harus diunggah.',
            'proposal_kegiatan.file' => 'Proposal kegiatan harus berupa file.',
            'proposal_kegiatan.mimes' => 'Proposal kegiatan harus berformat PDF.',
            'proposal_kegiatan.max' => 'Ukuran proposal kegiatan maksimal 5MB.',
            'proposal_permohonan.required' => 'Surat permohonan harus diunggah.',
            'proposal_permohonan.file' => 'Surat permohonan harus berupa file.',
            'proposal_permohonan.mimes' => 'Surat permohonan harus berformat PDF.',
            'proposal_permohonan.max' => 'Ukuran surat permohonan maksimal 5MB.',
        ];
    }
}
