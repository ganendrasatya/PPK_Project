<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #111; margin: 32px; }
        h1 { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
        p.sub { font-size: 10px; color: #555; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #0f766e; color: #fff; }
        th { padding: 7px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: .05em; }
        td { padding: 6px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background: #f9fafb; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: 600; }
        .aktif    { background: #d1fae5; color: #065f46; }
        .perbaikan{ background: #fef3c7; color: #92400e; }
        .nonaktif { background: #f3f4f6; color: #374151; }
        .num      { text-align: right; }
        footer { margin-top: 24px; font-size: 10px; color: #888; text-align: right; }
    </style>
</head>
<body>
    <h1>Rekap Okupansi &amp; Laporan Kerusakan</h1>
    <p class="sub">Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB &nbsp;|&nbsp; Total fasilitas: {{ $facilities->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Fasilitas</th>
                <th>Tipe</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th class="num">Reservasi</th>
                <th class="num">Laporan Kerusakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($facilities as $f)
            <tr>
                <td><strong>{{ $f->nama_fasilitas }}</strong></td>
                <td>{{ $f->tipe }}</td>
                <td>{{ $f->lokasi }}</td>
                <td>
                    @if($f->status === 'aktif')
                        <span class="badge aktif">Aktif</span>
                    @elseif($f->status === 'dalam_perbaikan')
                        <span class="badge perbaikan">Dalam Perbaikan</span>
                    @else
                        <span class="badge nonaktif">Nonaktif</span>
                    @endif
                </td>
                <td class="num">{{ $f->reservations_count }}</td>
                <td class="num">{{ $f->reports_count }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:16px;color:#888;">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <footer>ReservasiFasilitas Kampus &amp; Perkantoran &mdash; Dokumen ini digenerate otomatis.</footer>
</body>
</html>
