<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Facility;
use Illuminate\Support\Facades\DB;
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

        $reservation = DB::transaction(function () use ($validated, $startTime, $endTime, $request) {
            Facility::whereKey($validated['facility_id'])->lockForUpdate()->firstOrFail();

            $conflict = Reservation::where('facility_id', $validated['facility_id'])
                ->whereIn('status', ['pending', 'approved'])
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->exists();

            if ($conflict) {
                return null;
            }

            return Reservation::create([
                'user_id' => Auth::id(),
                'facility_id' => $validated['facility_id'],
                'purpose' => $validated['purpose'],
                'proposal_kegiatan_path' => $request->file('proposal_kegiatan')->store('proposals', 'public'),
                'proposal_permohonan_path' => $request->file('proposal_permohonan')->store('proposals', 'public'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'pending',
            ]);
        });

        if (! $reservation) {
            return back()->withInput()->with('error', 'Waktu yang dipilih sudah dibooking atau dalam proses persetujuan.');
        }

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

        public function approve(Reservation $reservation)
    {
        $result = DB::transaction(function () use ($reservation) {
            Facility::whereKey($reservation->facility_id)->lockForUpdate()->firstOrFail();
            $reservation->refresh();

            if ($reservation->status !== 'pending') {
                return 'processed';
            }
            if ($reservation->end_time->isPast()) {
                return 'expired';
            }

            $conflict = Reservation::where('facility_id', $reservation->facility_id)
                ->where('id', '!=', $reservation->id)
                ->where('status', 'approved')
                ->where('start_time', '<', $reservation->end_time)
                ->where('end_time', '>', $reservation->start_time)
                ->exists();

            if ($conflict) {
                return 'conflict';
            }

            $reservation->update(['status' => 'approved']);
            return 'ok';
        });

        return match ($result) {
            'processed' => back()->with('error', 'Reservasi ini sudah diproses sebelumnya.'),
            'expired'   => back()->with('error', 'Waktu reservasi sudah lewat dan tidak dapat disetujui.'),
            'conflict'  => back()->with('error', 'Tidak dapat menyetujui: jadwal bentrok dengan reservasi lain yang sudah disetujui.'),
            default     => back()->with('success', "Reservasi #{$reservation->id} berhasil disetujui."),
        };
    }

    public function reject(Request $request, Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Reservasi ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $reservation->update([
            'status' => 'rejected',
            'cancel_reason' => $validated['reason'],
        ]);

        return back()->with('success', "Reservasi #{$reservation->id} ditolak.");
    }
}
