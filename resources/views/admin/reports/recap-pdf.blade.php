<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Fasilitas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            background: #fff;
        }
        .header {
            background: #0F766E;
            color: #fff;
            padding: 18px 24px 14px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 10px;
            opacity: 0.85;
            margin-top: 3px;
        }
        .meta {
            display: flex;
            justify-content: space-between;
            padding: 0 24px 14px;
            font-size: 9px;
            color: #64748b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        thead tr {
            background: #0F766E;
            color: #fff;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
        }
        thead th.right { text-align: right; }
        thead th.center { text-align: center; }
        tbody tr:nth-child(even) { background: #f0fdfa; }
        tbody tr:nth-child(odd)  { background: #fff; }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        tbody td.right  { text-align: right; }
        tbody td.center { text-align: center; }
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 99px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-aktif           { background: #d1fae5; color: #065f46; }
        .badge-nonaktif        { background: #f1f5f9; color: #475569; }
        .badge-dalam_perbaikan { background: #fef3c7; color: #92400e; }
        .footer {
            margin-top: 20px;
            padding: 10px 24px;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        .totals-row td {
            background: #f8fafc;
            font-weight: bold;
            border-top: 2px solid #0F766E;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rekap Okupansi &amp; Laporan Kerusakan Fasilitas</h1>
        <p>Ringkasan pemakaian dan frekuensi kerusakan per fasilitas kampus &amp; perkantoran.</p>
    </div>

    <div class="meta">
        <span>Diekspor pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
        <span>Total fasilitas: {{ $facilities->count() }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="center" style="width:4%">No.</th>
                <th style="width:22%">Nama Fasilitas</th>
                <th style="width:14%">Tipe</th>
                <th style="width:20%">Lokasi</th>
                <th class="center" style="width:8%">Kapasitas</th>
                <th class="center" style="width:12%">Status</th>
                <th class="right" style="width:10%">Reservasi</th>
                <th class="right" style="width:10%">Lap. Kerusakan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $totalReservasi = 0; $totalLaporan = 0; @endphp
            @forelse($facilities as $facility)
                @php
                    $totalReservasi += $facility->reservations_count;
                    $totalLaporan   += $facility->reports_count;
                @endphp
                <tr>
                    <td class="center">{{ $no++ }}</td>
                    <td><strong>{{ $facility->nama_fasilitas }}</strong></td>
                    <td>{{ $facility->tipe }}</td>
                    <td>{{ $facility->lokasi }}</td>
                    <td class="center">{{ $facility->kapasitas }} org</td>
                    <td class="center">
                        <span class="badge badge-{{ $facility->status }}">
                            @if($facility->status === 'aktif') Aktif
                            @elseif($facility->status === 'nonaktif') Nonaktif
                            @else Dalam Perbaikan
                            @endif
                        </span>
                    </td>
                    <td class="right">{{ $facility->reservations_count }}</td>
                    <td class="right">{{ $facility->reports_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding: 20px; color: #94a3b8;">
                        Belum ada data fasilitas.
                    </td>
                </tr>
            @endforelse
            @if($facilities->count() > 0)
                <tr class="totals-row">
                    <td colspan="6" style="text-align:right; padding-right:10px;">TOTAL</td>
                    <td class="right">{{ $totalReservasi }}</td>
                    <td class="right">{{ $totalLaporan }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} ReservasiFasilitas Kampus &amp; Perkantoran — Dokumen ini digenerate otomatis oleh sistem.
    </div>
</body>
</html>
