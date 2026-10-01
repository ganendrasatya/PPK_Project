<?php

namespace App\Exports;

use App\Models\Facility;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RecapExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    public function collection()
    {
        return Facility::query()
            ->withCount(['reservations', 'reports'])
            ->orderBy('nama_fasilitas')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama Fasilitas',
            'Tipe',
            'Lokasi',
            'Kapasitas',
            'Status',
            'Jumlah Reservasi',
            'Jumlah Laporan Kerusakan',
        ];
    }

    public function map($facility): array
    {
        static $no = 0;
        $no++;

        $statusLabels = [
            'aktif'           => 'Aktif',
            'nonaktif'        => 'Nonaktif',
            'dalam_perbaikan' => 'Dalam Perbaikan',
        ];

        return [
            $no,
            $facility->nama_fasilitas,
            $facility->tipe,
            $facility->lokasi,
            $facility->kapasitas . ' orang',
            $statusLabels[$facility->status] ?? ucfirst($facility->status),
            $facility->reservations_count,
            $facility->reports_count,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F766E'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Rekap Fasilitas';
    }
}
