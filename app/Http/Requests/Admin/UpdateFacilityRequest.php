<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_fasilitas' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif,dalam_perbaikan'],
        ];
    }
}
