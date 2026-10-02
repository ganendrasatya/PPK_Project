<?php

namespace App\Exports;

use App\Models\Facility;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FacilitiesRecapExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function collection()
    {
        return Facility::withCount(['reservations', 'reports'])
            ->orderBy('nama_fasilitas')
            ->get()
            ->map(fn($f) => [
                $f->nama_fasilitas,
                $f->tipe,
                $f->lokasi,
                match($f->status) {
                    'aktif'          => 'Aktif',
                    'dalam_perbaikan'=> 'Dalam Perbaikan',
                    default          => 'Nonaktif',
                },
                $f->reservations_count,
                $f->reports_count,
            ]);
    }

    public function headings(): array
    {
        return ['Nama Fasilitas', 'Tipe', 'Lokasi', 'Status', 'Jumlah Reservasi', 'Jumlah Laporan Kerusakan'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Rekap Fasilitas';
    }
}
