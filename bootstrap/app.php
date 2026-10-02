<?php

use App\Http\Middleware\EnsureUserIsVerified;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Tunnel (cloudflared) berjalan di PC yang sama: percayai header X-Forwarded-* hanya dari localhost,
        // agar URL memakai https + domain tunnel dan request()->ip() berisi IP asli pengunjung.
        $middleware->trustProxies(at: ['127.0.0.1', '::1']);

        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'account.verified' => EnsureUserIsVerified::class, // <-- Taruh di sini
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Upload melebihi post_max_size: kembali ke form dengan pesan, bukan halaman error
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            return back()->with('error', 'File hanya bisa max 5MB');
        });
    })->create();