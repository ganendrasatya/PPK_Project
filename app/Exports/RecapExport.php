<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RecapExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(private Collection $facilities)
    {
    }

    public function collection(): Collection
    {
        return $this->facilities;
    }

    public function headings(): array
    {
        return ['Nama Fasilitas', 'Tipe', 'Lokasi', 'Status', 'Jumlah Reservasi', 'Jumlah Laporan Kerusakan'];
    }

    public function map($facility): array
    {
        return [
            $facility->nama_fasilitas,
            $facility->tipe,
            $facility->lokasi,
            $facility->status_label,
            $facility->reservations_count,
            $facility->reports_count,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
