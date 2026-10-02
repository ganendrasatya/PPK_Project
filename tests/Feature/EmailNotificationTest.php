<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\AccountApprovedNotification;
use App\Notifications\AccountRejectedNotification;
use App\Notifications\ReservationCancelledNotification;
use App\Notifications\ReservationRejectedNotification;
use App\Notifications\RegistrationPendingNotification;
use App\Notifications\ReservationApprovedNotification;
use App\Notifications\ReservationSubmittedNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected Facility $facility;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_registration_sends_pending_approval_email(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Mahasiswa Baru',
            'email' => 'baru@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'baru@example.com')->firstOrFail();
        Notification::assertSentTo($user, RegistrationPendingNotification::class);

        $mail = (new RegistrationPendingNotification)->toMail($user);
        $this->assertStringContainsString('Menunggu Persetujuan Admin', $mail->subject);
    }

    public function test_admin_approval_sends_account_approved_email(): void
    {
        Notification::fake();
        $admin = User::factory()->admin()->create();
        $pending = User::factory()->create(['role' => 'pengguna', 'status' => 'pending']);

        $this->actingAs($admin)->post("/admin/users/{$pending->id}/approve");

        Notification::assertSentTo($pending, AccountApprovedNotification::class);
    }

    public function test_approving_already_verified_user_does_not_send_email_again(): void
    {
        Notification::fake();
        $admin = User::factory()->admin()->create();
        $verified = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);

        $this->actingAs($admin)->post("/admin/users/{$verified->id}/approve");

        Notification::assertNothingSentTo($verified);
    }

    public function test_creating_reservation_sends_submitted_email(): void
    {
        Notification::fake();
        Storage::fake('local');
        $user = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);

        $this->actingAs($user)->post('/reservations', [
            'facility_id' => $this->facility->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'purpose' => 'Seminar himpunan',
            'proposal_kegiatan' => UploadedFile::fake()->create('kegiatan.pdf', 100, 'application/pdf'),
            'proposal_permohonan' => UploadedFile::fake()->create('permohonan.pdf', 100, 'application/pdf'),
        ])->assertSessionHas('success');

        Notification::assertSentTo($user, ReservationSubmittedNotification::class,
            fn ($notification) => $notification->reservation->purpose === 'Seminar himpunan');
    }

    public function test_petugas_approval_sends_reservation_approved_email(): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);
        $petugas = User::factory()->petugas()->create();
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Seminar',
            'start_time' => Carbon::tomorrow()->setTime(9, 0),
            'end_time' => Carbon::tomorrow()->setTime(10, 0),
            'status' => 'pending',
        ]);

        $this->actingAs($petugas)->post("/reservations/{$reservation->id}/approve")->assertSessionHas('success');

        Notification::assertSentTo($user, ReservationApprovedNotification::class);

        $mail = (new ReservationApprovedNotification($reservation->fresh()))->toMail($user);
        $this->assertSame(route('reservations.proof', $reservation), $mail->actionUrl);
    }

    public function test_failed_approval_does_not_send_email(): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);
        $petugas = User::factory()->petugas()->create();
        $this->facility->update(['status' => 'dalam_perbaikan']);
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Seminar',
            'start_time' => Carbon::tomorrow()->setTime(9, 0),
            'end_time' => Carbon::tomorrow()->setTime(10, 0),
            'status' => 'pending',
        ]);

        $this->actingAs($petugas)->post("/reservations/{$reservation->id}/approve")->assertSessionHas('error');

        Notification::assertNothingSent();
    }

    public function test_admin_rejection_sends_account_rejected_email(): void
    {
        Notification::fake();
        $admin = User::factory()->admin()->create();
        $pending = User::factory()->create(['role' => 'pengguna', 'status' => 'pending']);

        $this->actingAs($admin)->post("/admin/users/{$pending->id}/reject");

        Notification::assertSentTo($pending, AccountRejectedNotification::class);

        // Menolak ulang akun yang sudah ditolak tidak mengirim email lagi
        Notification::fake();
        $this->actingAs($admin)->post("/admin/users/{$pending->id}/reject");
        Notification::assertNothingSentTo($pending);
    }

    public function test_petugas_rejection_sends_reservation_rejected_email_with_reason(): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);
        $petugas = User::factory()->petugas()->create();
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Seminar',
            'start_time' => Carbon::tomorrow()->setTime(9, 0),
            'end_time' => Carbon::tomorrow()->setTime(10, 0),
            'status' => 'pending',
        ]);

        $this->actingAs($petugas)->post("/reservations/{$reservation->id}/reject", [
            'reason' => 'Bentrok dengan acara rektorat.',
        ])->assertSessionHas('success');

        Notification::assertSentTo($user, ReservationRejectedNotification::class);

        $mail = (new ReservationRejectedNotification($reservation->fresh()))->toMail($user);
        $this->assertStringContainsString('Ditolak', $mail->subject);
        $this->assertContains('**Alasan penolakan:** Bentrok dengan acara rektorat.', $mail->introLines);
    }

    public function test_petugas_cancellation_sends_cancelled_email_with_reason(): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);
        $petugas = User::factory()->petugas()->create();
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Seminar',
            'start_time' => Carbon::tomorrow()->setTime(9, 0),
            'end_time' => Carbon::tomorrow()->setTime(10, 0),
            'status' => 'approved',
        ]);

        $this->actingAs($petugas)->post("/reservations/{$reservation->id}/cancel-by-petugas", [
            'reason' => 'Listrik gedung padam total.',
        ])->assertSessionHas('success');

        Notification::assertSentTo($user, ReservationCancelledNotification::class);

        $mail = (new ReservationCancelledNotification($reservation->fresh()))->toMail($user);
        $this->assertStringContainsString('Dibatalkan', $mail->subject);
        $this->assertContains('**Alasan pembatalan:** Listrik gedung padam total.', $mail->introLines);
    }

    public function test_auto_cancellation_emails_each_affected_user(): void
    {
        Notification::fake();
        $petugas = User::factory()->petugas()->create();
        $alice = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);
        $bob = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);
        $carol = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);
        $make = fn (User $u, string $status, Carbon $start) => Reservation::create([
            'user_id' => $u->id,
            'facility_id' => $this->facility->id,
            'purpose' => 'Kegiatan',
            'start_time' => $start,
            'end_time' => $start->copy()->addHour(),
            'status' => $status,
        ]);
        $make($alice, 'approved', Carbon::tomorrow()->setTime(9, 0));
        $make($bob, 'pending', Carbon::tomorrow()->setTime(13, 0));
        $make($carol, 'approved', Carbon::now()->subDays(2)->setTime(9, 0)); // sudah lewat

        $this->actingAs($petugas)->patch("/facilities/{$this->facility->id}/status", ['status' => 'dalam_perbaikan']);

        Notification::assertSentTo($alice, ReservationCancelledNotification::class,
            fn ($n) => $n->reservation->cancel_reason === 'Dibatalkan otomatis: fasilitas sedang dalam perbaikan.');
        Notification::assertSentTo($bob, ReservationCancelledNotification::class);
        Notification::assertNothingSentTo($carol);
    }

    public function test_registration_still_succeeds_when_mail_server_fails(): void
    {
        // Server SMTP yang tidak bisa dihubungi
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => '127.0.0.1',
            'mail.mailers.smtp.port' => 1,
            'mail.mailers.smtp.timeout' => 1,
        ]);

        $this->post('/register', [
            'name' => 'Mahasiswa Baru',
            'email' => 'baru@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', ['email' => 'baru@example.com', 'status' => 'pending']);
    }
}
