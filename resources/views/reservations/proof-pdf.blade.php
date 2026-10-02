<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Bukti Persetujuan {{ $code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; margin: 0 10px; }
        .header { border-bottom: 3px solid #0f766e; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; margin: 0; color: #0f766e; }
        .header p { margin: 4px 0 0; color: #6b7280; }
        .badge { display: inline-block; background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; padding: 4px 12px; border-radius: 12px; font-weight: bold; font-size: 11px; }
        .code { float: right; font-size: 14px; font-weight: bold; color: #374151; }
        h2 { font-size: 13px; text-transform: uppercase; color: #0f766e; margin: 22px 0 8px; letter-spacing: .5px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 7px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        td.label { width: 35%; color: #6b7280; }
        td.value { font-weight: bold; }
        .note { margin-top: 26px; padding: 10px 12px; background: #f9fafb; border: 1px solid #e5e7eb; font-size: 11px; color: #4b5563; }
        .footer { margin-top: 30px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <span class="code">#{{ $code }}</span>
        <h1>Bukti Persetujuan Reservasi Fasilitas</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <span class="badge">DISETUJUI</span>

    <h2>Data Peminjam</h2>
    <table>
        <tr><td class="label">Nama</td><td class="value">{{ $reservation->user->name ?? '-' }}</td></tr>
        <tr><td class="label">Email</td><td class="value">{{ $reservation->user->email ?? '-' }}</td></tr>
    </table>

    <h2>Detail Reservasi</h2>
    <table>
        <tr><td class="label">Fasilitas</td><td class="value">{{ $reservation->facility->nama_fasilitas ?? '-' }}</td></tr>
        <tr><td class="label">Lokasi</td><td class="value">{{ $reservation->facility->lokasi ?? '-' }}</td></tr>
        <tr><td class="label">Tanggal</td><td class="value">{{ $reservation->start_time->locale('id')->translatedFormat('l, d F Y') }}</td></tr>
        <tr><td class="label">Waktu</td><td class="value">{{ $reservation->start_time->format('H:i') }} – {{ $reservation->end_time->format('H:i') }} WIB</td></tr>
        <tr><td class="label">Keperluan</td><td class="value">{{ $reservation->purpose }}</td></tr>
        <tr><td class="label">Tanggal Disetujui</td><td class="value">{{ $reservation->updated_at->locale('id')->translatedFormat('d F Y, H:i') }} WIB</td></tr>
    </table>

    <div class="note">
        Tunjukkan bukti ini kepada petugas fasilitas saat penggunaan. Persetujuan dapat dibatalkan oleh petugas
        apabila terjadi kondisi darurat; status terbaru selalu dapat dicek di halaman Reservasi Saya.
    </div>

    <div class="footer">Dicetak {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB &middot; Dokumen ini dibuat otomatis oleh sistem.</div>
</body>
</html>
