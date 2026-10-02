<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThirdReviewFixesTest extends TestCase
{
    use RefreshDatabase;

    protected Facility $facility;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'pengguna', 'status' => 'verified']);
        $this->facility = Facility::create([
            'nama_fasilitas' => 'Aula Ma\'had "Utama"',
            'tipe' => 'Aula',
            'lokasi' => 'Gedung Utama',
            'kapasitas' => 300,
            'status' => 'aktif',
            'jam_buka' => '07:00',
            'jam_tutup' => '18:00',
        ]);
    }

    public function test_report_photo_input_is_not_inside_x_if_template(): void
    {
        $html = $this->actingAs($this->user)->get('/reports')->getContent();

        // Input foto harus tetap ada di form saat preview tampil (x-if akan menghapusnya dari DOM)
        $this->assertMatchesRegularExpression('/<input id="photo-upload"[^>]*name="photo"/', $html);
        $templates = preg_match_all('/<template x-if="[^"]*filePreview[^"]*">.*?<\/template>/s', $html, $m);
        $this->assertSame(0, $templates, 'Preview foto tidak boleh memakai x-if');
    }

    public function test_report_photo_over_5mb_shows_requested_message(): void
    {
        $this->actingAs($this->user)->post('/reports', [
            'facility_id' => $this->facility->id,
            'category' => 'AC & Ventilasi',
            'urgency' => 'sedang',
            'title' => 'AC bocor',
            'description' => 'AC meneteskan air terus-menerus.',
            'photo' => \Illuminate\Http\UploadedFile::fake()->image('besar.jpg')->size(6000),
        ])->assertSessionHasErrors(['photo' => 'File hanya bisa max 5MB']);

        $this->assertSame(0, Report::count());
    }

    public function test_confirm_dialogs_are_safe_for_quotes_in_facility_name(): void
    {
        $petugas = User::factory()->petugas()->create();

        Report::create([
            'user_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'title' => 'Lampu Mati',
            'category' => 'Kelistrikan & Lampu',
            'description' => 'Lampu padam total.',
            'status' => 'baru',
        ]);

        foreach (['/petugas', '/reports'] as $page) {
            $html = $this->actingAs($petugas)->get($page)->getContent();
            preg_match_all('/onclick="return confirm\(([^"]*)\)"/', $html, $m);

            $this->assertNotEmpty($m[1], "Tidak ada tombol konfirmasi di {$page}");
            foreach ($m[1] as $arg) {
                // Tanda petik/kutip di-escape untuk JavaScript, bukan sebagai entitas HTML
                $this->assertStringNotContainsString('&#039;', $arg);
                $this->assertStringContainsString('Ma\\u0027had \\u0022Utama\\u0022', $arg);
            }
        }
    }

    public function test_validation_and_login_messages_are_in_indonesian(): void
    {
        $this->post('/register', [
            'name' => '',
            'email' => 'bukan-email',
            'password' => '123',
            'password_confirmation' => '1234',
        ])->assertSessionHasErrors([
            'name' => 'Nama wajib diisi.',
            'email' => 'Email harus berupa alamat email yang valid.',
        ]);

        $this->post('/login', ['email' => 'tidak@ada.com', 'password' => 'salah'])
            ->assertSessionHasErrors(['email' => 'Email atau kata sandi salah.']);
    }

    public function test_uppercase_email_is_normalized_on_register_and_login(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Budi',
            'email' => '  Budi@Gmail.COM ',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasNoErrors();

        $budi = User::where('email', 'budi@gmail.com')->firstOrFail();
        $budi->update(['status' => 'verified']);

        $this->post('/login', ['email' => 'BUDI@gmail.com', 'password' => 'password']);
        $this->assertAuthenticatedAs($budi);
    }

    public function test_catalog_detail_link_keeps_selected_date(): void
    {
        $date = Carbon::today()->addDays(5)->format('Y-m-d');

        $this->actingAs($this->user)->get("/?date={$date}")
            ->assertSee(route('catalog.show', ['facility' => $this->facility, 'date' => $date]), false)
            ->assertDontSee('Status Hari Ini')
            ->assertSee('Sesi per 30 menit');

        $this->actingAs($this->user)->get('/')->assertSee('Status Hari Ini');
    }
}
