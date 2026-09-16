<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_fasilitas' => fake()->words(3, true),
            'tipe' => fake()->randomElement(['Ruang Kelas', 'Aula', 'Laboratorium', 'Lapangan', 'Alat']),
            'lokasi' => fake()->streetName(),
            'kapasitas' => fake()->numberBetween(10, 100),
            'deskripsi' => fake()->sentence(),
            'status' => 'aktif',
        ];
    }
}
