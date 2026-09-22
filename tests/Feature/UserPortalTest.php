<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserPortalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;
    protected Facility $facility;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'pengguna',
            'status' => 'verified',
        ]);

        $this->admin = User::factory()->admin()->create();

        $this->facility = Facility::create([
            'nama_fasilitas' => 'Lapangan Basket Utama',
            'tipe' => 'Olahraga',
            'lokasi' => 'Gedung Olahraga Lt. 1',
            'kapasitas' => 300,
            'deskripsi' => 'Lapangan basket standar nasional',
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_catalog(): void
    {
        $response = $this->actingAs($this->user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Lapangan Basket Utama');
        $response->assertSee('07.00–18.00 WIB');
    }

    public function test_user_can_view_facility_detail_and_slots(): void
    {
        $response = $this->actingAs($this->user)->get('/facilities/' . $this->facility->id);
        $response->assertStatus(200);
        $response->assertSee('Ketersediaan Slot');
        $response->assertSee('07:00');
        $response->assertSee('17:30');
    }

    public function test_slots_api_returns_correct_time_range(): void
    {
        $response = $this->actingAs($this->user)->getJson('/facilities/' . $this->facility->id . '/slots');
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertNotEmpty($data);
        $this->assertEquals('07:00', $data[0]['time']);
        $this->assertEquals('17:30', end($data)['time']);
    }

    public function test_user_can_create_reservation_with_required_documents(): void
    {
        Storage::fake('public');

        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $kegiatan = UploadedFile::fake()->create('proposal_kegiatan.pdf', 100, 'application/pdf');
        $permohonan = UploadedFile::fake()->create('proposal_permohonan.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->user)->post('/reservations', [
            'facility_id' => $this->facility->id,
            'date' => $tomorrow,
            'start_time' => '09:00',
            'end_time' => '10:00',
            'purpose' => 'Latihan rutin UKM Basket',
            'proposal_kegiatan' => $kegiatan,
            'proposal_permohonan' => $permohonan,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservations', [
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Latihan rutin UKM Basket',
            'status' => 'pending',
        ]);
    }

    public function test_user_can_view_reservations_history(): void
    {
        $startTime = Carbon::tomorrow()->setTime(9, 0);
        $endTime = Carbon::tomorrow()->setTime(10, 0);

        Reservation::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Kegiatan Fakultas',
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->get('/reservations');
        $response->assertStatus(200);
        $response->assertSee('Reservasi Saya');
        $response->assertSee('Kegiatan Fakultas');
        $response->assertSee('Lapangan Basket Utama');
    }

    public function test_user_can_cancel_pending_reservation(): void
    {
        $reservation = Reservation::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Kegiatan Dibatalkan',
            'start_time' => Carbon::tomorrow()->setTime(9, 0),
            'end_time' => Carbon::tomorrow()->setTime(10, 0),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->post("/reservations/{$reservation->id}/cancel");
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_user_can_submit_damage_report(): void
    {
        Storage::fake('public');
        $photo = UploadedFile::fake()->image('kerusakan.jpg');

        $response = $this->actingAs($this->user)->post('/reports', [
            'facility_id' => $this->facility->id,
            'category' => 'Kerusakan Sedang',
            'title' => 'Ring Basket Rusak',
            'description' => 'Ring basket bengkok dan jaringnya terlepas',
            'photo' => $photo,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reports', [
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'title' => 'Ring Basket Rusak',
            'category' => 'Kerusakan Sedang',
            'status' => 'baru',
        ]);
    }

    public function test_user_can_view_damage_reports(): void
    {
        Report::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'title' => 'Lampu Mati',
            'category' => 'Kerusakan Ringan',
            'description' => 'Lampu tribun sisi timur padam',
            'status' => 'baru',
        ]);

        $response = $this->actingAs($this->user)->get('/reports');
        $response->assertStatus(200);
        $response->assertSee('Laporan & Kerusakan', false);
        $response->assertSee('Lampu Mati');
    }

    public function test_admin_can_update_damage_report_status(): void
    {
        $report = Report::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'title' => 'Kerusakan AC',
            'category' => 'Kerusakan Sedang',
            'description' => 'AC meneteskan air terus menerus',
            'status' => 'baru',
        ]);

        $response = $this->actingAs($this->admin)->patch("/reports/{$report->id}/status", [
            'status' => 'diproses',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'diproses',
        ]);
    }
}

