<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PetugasController extends Controller
{
    public function dashboard(Request $request): View
    {
        $tab = $request->input('tab', 'pending');

        $query = Reservation::with(['facility', 'user']);

        if (in_array($tab, ['pending', 'approved', 'rejected', 'cancelled'])) {
            $query->where('status', $tab);
        }

        $reservations = $query->orderBy('start_time')->paginate(10)->withQueryString();

        $statusCounts = Reservation::toBase()->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');
        $counts = [
            'pending' => $statusCounts['pending'] ?? 0,
            'approved' => $statusCounts['approved'] ?? 0,
            'rejected' => $statusCounts['rejected'] ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0,
            'semua' => $statusCounts->sum(),
        ];

        $allFacilities = Facility::orderBy('nama_fasilitas')->get();

        return view('petugas.dashboard', [
            'reservations' => $reservations,
            'counts' => $counts,
            'tab' => $tab,
            'activeFacilities' => $allFacilities->where('status', 'aktif')->count(),
            'inRepairFacilities' => $allFacilities->where('status', 'dalam_perbaikan')->count(),
            'allFacilities' => $allFacilities,
            'openReports' => Report::whereIn('status', ['baru', 'diproses'])->count(),
        ]);
    }
}

