<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        $user = Auth::user();

        $query = Reservation::with('facility')->where('user_id', $user->id);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('facility', function($qF) use ($search) {
                      $qF->where('nama_fasilitas', 'like', "%{$search}%");
                  });
            });
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats calculation
        $allUserReservations = Reservation::where('user_id', $user->id)->get();
        $totalCount = $allUserReservations->count();
        $activeCount = $allUserReservations->where('status', 'approved')->where('end_time', '>', Carbon::now())->count();
        $approvedCount = $allUserReservations->where('status', 'approved')->count();
        $approvalRate = $totalCount > 0 ? round(($approvedCount / $totalCount) * 100, 1) : 0;

        $pendingCount = $allUserReservations->where('status', 'pending')->count();

        $stats = (object) [
            'total' => $totalCount,
            'active' => $activeCount,
            'approval_rate' => $approvalRate,
            'pending' => $pendingCount,
        ];

        return view('reservations.index', compact('reservations', 'stats', 'status', 'search'));
    }

    public function store(StoreReservationRequest $request)
    {
        $validated = $request->validated();
        
        $startTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $endTime = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);

        // Check for slot conflicts
        $conflicts = Reservation::where('facility_id', $validated['facility_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })->exists();

        if ($conflicts) {
            return back()->withInput()->with('error', 'Waktu yang dipilih sudah dibooking atau dalam proses persetujuan.');
        }

        // Store files
        $proposalKegiatanPath = $request->file('proposal_kegiatan')->store('proposals', 'public');
        $proposalPermohonanPath = $request->file('proposal_permohonan')->store('proposals', 'public');

        Reservation::create([
            'user_id' => Auth::id(),
            'facility_id' => $validated['facility_id'],
            'purpose' => $validated['purpose'],
            'proposal_kegiatan_path' => $proposalKegiatanPath,
            'proposal_permohonan_path' => $proposalPermohonanPath,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Reservasi berhasil dibuat. Menunggu persetujuan admin.');
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Hanya reservasi dengan status pending yang dapat dibatalkan.');
        }

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
