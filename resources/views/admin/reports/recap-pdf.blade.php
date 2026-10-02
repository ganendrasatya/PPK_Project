<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Fasilitas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .meta { color: #6b7280; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0f766e; color: #fff; text-align: left; padding: 6px 8px; font-size: 10px; text-transform: uppercase; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background: #f9fafb; }
        .num { text-align: right; }
        tfoot td { font-weight: bold; border-top: 2px solid #0f766e; background: #fff; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: 600; }
        .aktif     { background: #d1fae5; color: #065f46; }
        .perbaikan { background: #fef3c7; color: #92400e; }
        .nonaktif  { background: #f3f4f6; color: #374151; }
    </style>
</head>
<body>
    <h1>Rekap Okupansi &amp; Laporan Kerusakan</h1>
    <div class="meta">Dicetak {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB &middot; {{ $facilities->count() }} fasilitas</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
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
                <td>{{ $loop->iteration }}</td>
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
            <tr><td colspan="7" style="text-align:center;padding:16px;color:#888;">Belum ada data fasilitas tercatat.</td></tr>
            @endforelse
        </tbody>
        @if ($facilities->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="num">{{ $facilities->sum('reservations_count') }}</td>
                <td class="num">{{ $facilities->sum('reports_count') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>
