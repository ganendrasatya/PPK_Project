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
            'category' => 'Mebel & Fisik Pintu',
            'urgency' => 'sedang',
            'title' => 'Ring Basket Rusak',
            'description' => 'Ring basket bengkok dan jaringnya terlepas',
            'photo' => $photo,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reports', [
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'title' => 'Ring Basket Rusak',
            'category' => 'Mebel & Fisik Pintu',
            'urgency' => 'sedang',
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

    public function test_user_can_filter_facilities_by_capacity(): void
    {
        $response = $this->actingAs($this->user)->get('/?min_capacity=30');
        $response->assertStatus(200);
        $response->assertSee($this->facility->nama_fasilitas);

        $responseEmpty = $this->actingAs($this->user)->get('/?min_capacity=999');
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertDontSee($this->facility->nama_fasilitas);
    }

    public function test_user_cannot_cancel_past_reservation(): void
    {
        $pastReservation = Reservation::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Rapat kemarin',
            'proposal_kegiatan_path' => 'proposals/sample1.pdf',
            'proposal_permohonan_path' => 'proposals/sample2.pdf',
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now()->subHours(1),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->post("/reservations/{$pastReservation->id}/cancel");
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('reservations', [
            'id' => $pastReservation->id,
            'status' => 'pending',
        ]);
    }

    public function test_petugas_can_cancel_approved_reservation_with_reason(): void
    {
        $petugas = User::factory()->petugas()->create();
        $reservation = Reservation::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Seminar Mahasiswa',
            'proposal_kegiatan_path' => 'proposals/sample1.pdf',
            'proposal_permohonan_path' => 'proposals/sample2.pdf',
            'start_time' => Carbon::now()->addDays(1)->setHour(9)->setMinute(0),
            'end_time' => Carbon::now()->addDays(1)->setHour(11)->setMinute(0),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($petugas)->post("/reservations/{$reservation->id}/cancel-by-petugas", [
            'reason' => 'Fasilitas mendadak mati listrik total dan renovasi darurat.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'cancelled',
            'cancel_reason' => 'Fasilitas mendadak mati listrik total dan renovasi darurat.',
        ]);
    }

    public function test_petugas_cannot_cancel_approved_reservation_without_reason(): void
    {
        $petugas = User::factory()->petugas()->create();
        $reservation = Reservation::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Latihan Olahraga',
            'proposal_kegiatan_path' => 'proposals/sample1.pdf',
            'proposal_permohonan_path' => 'proposals/sample2.pdf',
            'start_time' => Carbon::now()->addDays(1)->setHour(13)->setMinute(0),
            'end_time' => Carbon::now()->addDays(1)->setHour(15)->setMinute(0),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($petugas)->post("/reservations/{$reservation->id}/cancel-by-petugas", [
            'reason' => '',
        ]);

        $response->assertSessionHasErrors(['reason']);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'approved',
        ]);
    }

    public function test_regular_user_cannot_cancel_approved_reservation_via_petugas_route(): void
    {
        $reservation = Reservation::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Rapat Internal',
            'proposal_kegiatan_path' => 'proposals/sample1.pdf',
            'proposal_permohonan_path' => 'proposals/sample2.pdf',
            'start_time' => Carbon::now()->addDays(1)->setHour(10)->setMinute(0),
            'end_time' => Carbon::now()->addDays(1)->setHour(12)->setMinute(0),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->user)->post("/reservations/{$reservation->id}/cancel-by-petugas", [
            'reason' => 'Ingin batalkan sendiri',
        ]);

        $response->assertStatus(403);
    }

    public function test_petugas_cannot_cancel_past_approved_reservation(): void
    {
        $petugas = User::factory()->petugas()->create();
        $pastReservation = Reservation::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Kegiatan kemarin',
            'proposal_kegiatan_path' => 'proposals/sample1.pdf',
            'proposal_permohonan_path' => 'proposals/sample2.pdf',
            'start_time' => Carbon::now()->subDays(2)->setHour(9)->setMinute(0),
            'end_time' => Carbon::now()->subDays(2)->setHour(11)->setMinute(0),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($petugas)->post("/reservations/{$pastReservation->id}/cancel-by-petugas", [
            'reason' => 'Mau membatalkan yang sudah lewat',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('reservations', [
            'id' => $pastReservation->id,
            'status' => 'approved',
        ]);
    }

    public function test_petugas_can_resolve_damage_report_with_resolution_note(): void
    {
        $petugas = User::factory()->petugas()->create();
        $report = Report::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'title' => 'Pintu Kamar Mandi Rusak',
            'category' => 'Kerusakan Ringan',
            'description' => 'Gagang pintu kamar mandi terlepas.',
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($petugas)->patch("/reports/{$report->id}/status", [
            'status' => 'selesai',
            'resolution_note' => 'Gagang pintu baru sudah dipasang dan berfungsi normal.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'selesai',
            'resolution_note' => 'Gagang pintu baru sudah dipasang dan berfungsi normal.',
        ]);

        // Cek bahwa catatan muncul di halaman reports pengguna
        $pageResponse = $this->actingAs($this->user)->get('/reports');
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Gagang pintu baru sudah dipasang dan berfungsi normal.');
    }

    public function test_petugas_cannot_resolve_damage_report_without_resolution_note(): void
    {
        $petugas = User::factory()->petugas()->create();
        $report = Report::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'title' => 'Lampu Lapangan Mati',
            'category' => 'Kerusakan Sedang',
            'description' => 'Lampu sisi timur padam total.',
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($petugas)->patch("/reports/{$report->id}/status", [
            'status' => 'selesai',
            'resolution_note' => '',
        ]);

        $response->assertSessionHasErrors(['resolution_note']);
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'diproses',
        ]);
    }

    public function test_petugas_can_update_facility_status(): void
    {
        $petugas = User::factory()->petugas()->create();

        // Tandai dalam perbaikan
        $response = $this->actingAs($petugas)->patch("/facilities/{$this->facility->id}/status", [
            'status' => 'dalam_perbaikan',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('facilities', [
            'id' => $this->facility->id,
            'status' => 'dalam_perbaikan',
        ]);

        // Kembalikan ke aktif
        $response2 = $this->actingAs($petugas)->patch("/facilities/{$this->facility->id}/status", [
            'status' => 'aktif',
        ]);

        $response2->assertSessionHas('success');
        $this->assertDatabaseHas('facilities', [
            'id' => $this->facility->id,
            'status' => 'aktif',
        ]);
    }

    public function test_regular_user_cannot_update_facility_status(): void
    {
        $response = $this->actingAs($this->user)->patch("/facilities/{$this->facility->id}/status", [
            'status' => 'dalam_perbaikan',
        ]);

        $response->assertStatus(403);
    }

    public function test_resolving_damage_report_can_reactivate_facility(): void
    {
        $petugas = User::factory()->petugas()->create();
        $this->facility->update(['status' => 'dalam_perbaikan']);

        $report = Report::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'title' => 'Kerusakan Pipa Air',
            'category' => 'Kerusakan Berat',
            'description' => 'Pipa bocor membanjiri lantai lapangan.',
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($petugas)->patch("/reports/{$report->id}/status", [
            'status' => 'selesai',
            'resolution_note' => 'Pipa air sudah diganti dan area dibersihkan.',
            'reactivate_facility' => 1,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'selesai',
        ]);
        $this->assertDatabaseHas('facilities', [
            'id' => $this->facility->id,
            'status' => 'aktif',
        ]);
    }

    public function test_admin_can_create_petugas_and_pengguna_accounts(): void
    {
        $admin = User::factory()->admin()->create();

        // View form
        $response = $this->actingAs($admin)->get(route('admin.users.create', ['role' => 'petugas']));
        $response->assertOk();
        $response->assertSee('Tambah Akun Petugas');

        // Store petugas
        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Petugas Baru',
            'email' => 'petugas.baru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'petugas',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Petugas Baru',
            'email' => 'petugas.baru@example.com',
            'role' => 'petugas',
            'status' => 'verified',
        ]);

        // Store pengguna
        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Pengguna Baru',
            'email' => 'pengguna.baru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'pengguna',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Pengguna Baru',
            'email' => 'pengguna.baru@example.com',
            'role' => 'pengguna',
            'status' => 'verified',
        ]);
    }
}

