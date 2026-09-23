<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'category' => ['required', 'string', Rule::in(['Kelistrikan & Lampu', 'AC & Ventilasi', 'IT, PC & Kabel LAN', 'Mebel & Fisik Pintu'])],
            'urgency' => ['required', 'string', 'in:rendah,sedang,mendesak'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:500'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'facility_id.required' => 'Fasilitas harus dipilih.',
            'facility_id.exists' => 'Fasilitas tidak valid.',
            'category.required' => 'Kategori sarana/prasarana harus dipilih.',
            'category.in' => 'Kategori sarana/prasarana tidak valid.',
            'urgency.required' => 'Tingkat urgensi harus dipilih.',
            'urgency.in' => 'Tingkat urgensi tidak valid.',
            'title.required' => 'Judul kendala harus diisi.',
            'title.string' => 'Judul kendala harus berupa teks.',
            'title.max' => 'Judul kendala maksimal 255 karakter.',
            'description.required' => 'Deskripsi kerusakan harus diisi.',
            'description.string' => 'Deskripsi kerusakan harus berupa teks.',
            'description.min' => 'Deskripsi kerusakan minimal 10 karakter.',
            'description.max' => 'Deskripsi kerusakan maksimal 500 karakter.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus berupa JPG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 5MB.',
        ];
    }
}
