<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDamageReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'facility_id' => ['required', 'exists:facilities,id'],
            'category' => ['required', 'string', 'in:Kerusakan Ringan,Kerusakan Sedang,Kerusakan Berat'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'facility_id.required' => 'Fasilitas harus dipilih.',
            'facility_id.exists' => 'Fasilitas tidak valid.',
            'category.required' => 'Kategori kerusakan harus dipilih.',
            'category.in' => 'Kategori kerusakan tidak valid.',
            'title.required' => 'Judul laporan harus diisi.',
            'title.string' => 'Judul laporan harus berupa teks.',
            'title.max' => 'Judul laporan maksimal 255 karakter.',
            'description.required' => 'Deskripsi kerusakan harus diisi.',
            'description.string' => 'Deskripsi kerusakan harus berupa teks.',
            'description.min' => 'Deskripsi kerusakan minimal 10 karakter.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 5MB.',
        ];
    }
}
