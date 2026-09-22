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

        $statusMap = [
            'pending' => 'pending',
            'approved' => 'approved',
            'rejected' => 'rejected',
            'cancelled' => 'cancelled',
        ];

        $query = Reservation::with(['facility', 'user']);

        if ($tab !== 'semua' && isset($statusMap[$tab])) {
            $query->where('status', $statusMap[$tab]);
        }

        $reservations = $query->orderBy('start_time')->paginate(10)->withQueryString();

        $counts = [
            'pending' => Reservation::where('status', 'pending')->count(),
            'approved' => Reservation::where('status', 'approved')->count(),
            'rejected' => Reservation::where('status', 'rejected')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'semua' => Reservation::count(),
        ];

        return view('petugas.dashboard', [
            'reservations' => $reservations,
            'counts' => $counts,
            'tab' => $tab,
            'activeFacilities' => Facility::where('status', 'aktif')->count(),
            'openReports' => Report::whereIn('status', ['baru', 'diproses'])->count(),
        ]);
    }
}
