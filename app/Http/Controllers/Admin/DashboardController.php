<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalFacilities' => Facility::count(),
            'pendingUsers' => User::where('status', 'pending')->count(),
            'pendingReservations' => Reservation::where('status', 'pending')->count(),
            'openReports' => Report::whereIn('status', ['baru', 'diproses'])->count(),
        ]);
    }
}
