<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Setiap pendaftaran mengirim email, jadi batasi per IP agar form tidak dipakai untuk spam
        RateLimiter::for('register', function (Request $request) {
            $tooMany = fn () => back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'Terlalu banyak percobaan pendaftaran dari jaringan Anda. Silakan coba lagi nanti.']);

            return [
                Limit::perMinute(3)->by('register-min:' . $request->ip())->response($tooMany),
                Limit::perHour(10)->by('register-hour:' . $request->ip())->response($tooMany),
            ];
        });
    }
}
