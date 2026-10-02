<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecapTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();

        Facility::create([
            'nama_fasilitas' => 'Aula Serbaguna',
            'tipe' => 'Aula',
            'lokasi' => 'Gedung Utama',
            'kapasitas' => 300,
            'status' => 'dalam_perbaikan',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);
    }

    public function test_admin_can_view_recap_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/recap');

        $response->assertStatus(200);
        $response->assertSee('Aula Serbaguna');
    }

    public function test_admin_can_export_recap_as_csv_excel_and_pdf(): void
    {
        $this->actingAs($this->admin)->get('/admin/recap/export.csv')
            ->assertStatus(200)
            ->assertDownload('rekap-fasilitas.csv');

        $this->actingAs($this->admin)->get('/admin/recap/export.xlsx')
            ->assertStatus(200)
            ->assertDownload('rekap-fasilitas.xlsx');

        $pdf = $this->actingAs($this->admin)->get('/admin/recap/export.pdf');
        $pdf->assertStatus(200)->assertDownload('rekap-fasilitas.pdf');
        $this->assertStringStartsWith('%PDF', $pdf->getContent());
    }

    public function test_non_admin_cannot_export_recap(): void
    {
        $petugas = User::factory()->petugas()->create();

        $this->actingAs($petugas)->get('/admin/recap/export.xlsx')->assertStatus(403);
        $this->actingAs($petugas)->get('/admin/recap/export.pdf')->assertStatus(403);
    }
}
