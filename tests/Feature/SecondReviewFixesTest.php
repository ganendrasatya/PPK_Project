<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SecondReviewFixesTest extends TestCase
{
    use RefreshDatabase;

    protected Facility $facility;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);
        $this->facility = Facility::create([
            'nama_fasilitas' => 'Aula Serbaguna',
            'tipe' => 'Aula',
            'lokasi' => 'Gedung Utama',
            'kapasitas' => 300,
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);
    }

    private function reservation(string $status, Carbon $start): Reservation
    {
        return Reservation::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Kegiatan',
            'start_time' => $start,
            'end_time' => $start->copy()->addHour(),
            'status' => $status,
        ]);
    }

    public function test_admin_pages_show_error_messages(): void
    {
        $admin = User::factory()->admin()->create();
        $this->reservation('approved', Carbon::tomorrow()->setTime(9, 0));

        $this->actingAs($admin)
            ->from('/admin/facilities')
            ->followingRedirects()
            ->delete("/admin/facilities/{$this->facility->id}")
            ->assertSee('tidak dapat dihapus');

        $this->assertModelExists($this->facility);
    }

    public function test_stale_pending_reservations_expire_when_petugas_opens_dashboard(): void
    {
        $petugas = User::factory()->petugas()->create();
        $stale = $this->reservation('pending', Carbon::now()->subDays(2)->setTime(9, 0));
        $upcoming = $this->reservation('pending', Carbon::tomorrow()->setTime(9, 0));

        $this->actingAs($petugas)->get('/petugas')->assertOk();

        $this->assertDatabaseHas('reservations', [
            'id' => $stale->id,
            'status' => 'rejected',
            'cancel_reason' => Reservation::EXPIRED_REASON,
        ]);
        $this->assertDatabaseHas('reservations', ['id' => $upcoming->id, 'status' => 'pending']);
    }

    public function test_user_sees_expired_reason_and_count_is_not_pending(): void
    {
        $this->reservation('pending', Carbon::now()->subDays(2)->setTime(9, 0));

        $this->actingAs($this->user)->get('/reservations')
            ->assertSee('Kedaluwarsa')
            ->assertSee('0 menunggu verifikasi');
    }

    public function test_approving_reservation_that_already_started_marks_it_expired(): void
    {
        $petugas = User::factory()->petugas()->create();
        $started = $this->reservation('pending', Carbon::now()->subMinutes(30));

        $this->actingAs($petugas)->post("/reservations/{$started->id}/approve")->assertSessionHas('error');

        $this->assertDatabaseHas('reservations', ['id' => $started->id, 'status' => 'rejected']);
    }

    public function test_expire_command_marks_stale_pending(): void
    {
        $stale = $this->reservation('pending', Carbon::now()->subHours(3));

        $this->artisan('reservations:expire')->assertSuccessful();

        $this->assertDatabaseHas('reservations', ['id' => $stale->id, 'status' => 'rejected']);
    }

    public function test_registration_is_rate_limited_per_ip(): void
    {
        Notification::fake();

        for ($i = 1; $i <= 4; $i++) {
            $response = $this->post('/register', [
                'name' => "Pendaftar {$i}",
                'email' => "pendaftar{$i}@example.com",
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);
        }

        // Percobaan ke-4 dalam satu menit ditolak dengan pesan, bukan halaman 429
        $response->assertRedirect()->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 4); // 1 user setUp + 3 pendaftar
        $this->assertDatabaseMissing('users', ['email' => 'pendaftar4@example.com']);
    }

    public function test_password_reset_email_is_in_indonesian(): void
    {
        Notification::fake();

        $this->post('/forgot-password', ['email' => $this->user->email]);

        Notification::assertSentTo($this->user, ResetPassword::class, function ($notification, $channels, $notifiable, $locale) {
            app()->setLocale($locale);
            $mail = $notification->toMail($notifiable);

            return $locale === 'id'
                && $mail->subject === 'Atur Ulang Kata Sandi Anda'
                && $mail->actionText === 'Atur Ulang Kata Sandi';
        });
    }
}
