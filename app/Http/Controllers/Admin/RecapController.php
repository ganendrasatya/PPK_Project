<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecapController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.recap', ['facilities' => $this->recapData()]);
    }

    public function exportCsv(): StreamedResponse
    {
        $facilities = $this->recapData();

        $callback = function () use ($facilities) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Nama Fasilitas', 'Tipe', 'Lokasi', 'Status', 'Jumlah Reservasi', 'Jumlah Laporan Kerusakan']);

            foreach ($facilities as $facility) {
                fputcsv($handle, [
                    $facility->nama_fasilitas,
                    $facility->tipe,
                    $facility->lokasi,
                    $facility->status,
                    $facility->reservations_count,
                    $facility->reports_count,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, 'rekap-fasilitas.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function recapData()
    {
        return Facility::query()
            ->withCount(['reservations', 'reports'])
            ->orderBy('nama_fasilitas')
            ->get();
    }
}
