<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedType = $request->input('type');
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $query = Facility::where('status', 'aktif');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_fasilitas', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($selectedType) {
            $query->where('tipe', $selectedType);
        }

        $facilities = $query->paginate(12);
        
        $types = Facility::select('tipe')->distinct()->pluck('tipe');

        // Calculate available slots for each facility
        foreach ($facilities as $facility) {
            $slots = $this->generateSlots($facility, $date);
            $availableSlotsCount = collect($slots)->where('available', true)->count();
            $facility->available_slots_count = $availableSlotsCount;
            $facility->total_slots_count = count($slots);
        }

        return view('catalog.index', compact('facilities', 'types', 'selectedType', 'search', 'date'));
    }

    public function show(Facility $facility, Request $request)
    {
        if ($facility->status !== 'aktif') {
            abort(404);
        }

        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $slots = $this->generateSlots($facility, $date);

        return view('catalog.show', compact('facility', 'slots', 'date'));
    }

    public function slots(Facility $facility, Request $request)
    {
        if ($facility->status !== 'aktif') {
            return response()->json(['error' => 'Facility not active'], 400);
        }

        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $slots = $this->generateSlots($facility, $date);

        return response()->json($slots);
    }

    private function generateSlots(Facility $facility, string $date)
    {
        $jamBuka = is_string($facility->jam_buka) ? $facility->jam_buka : $facility->jam_buka->format('H:i');
        $jamTutup = is_string($facility->jam_tutup) ? $facility->jam_tutup : $facility->jam_tutup->format('H:i');
        $startTime = Carbon::parse($jamBuka);
        $endTime = Carbon::parse($jamTutup);
        $slots = [];

        // Fetch reservations for this date
        $reservations = Reservation::where('facility_id', $facility->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_time', $date)
            ->get();

        $currentTime = $startTime->copy();

        while ($currentTime < $endTime) {
            $slotStart = $currentTime->copy();
            $slotEnd = $currentTime->copy()->addMinutes(30);

            // If slot ends after jam_tutup, break (though shouldn't happen with proper times)
            if ($slotEnd > $endTime) {
                break;
            }

            // Check if available
            $available = true;
            $slotStartDatetime = Carbon::parse($date . ' ' . $slotStart->format('H:i:s'));
            $slotEndDatetime = Carbon::parse($date . ' ' . $slotEnd->format('H:i:s'));

            foreach ($reservations as $reservation) {
                // overlap condition: start < r_end AND end > r_start
                if ($slotStartDatetime < $reservation->end_time && $slotEndDatetime > $reservation->start_time) {
                    $available = false;
                    break;
                }
            }
            
            // If the date is today, make past slots unavailable
            if (Carbon::parse($date)->isToday()) {
                if ($slotStartDatetime < Carbon::now()) {
                    $available = false;
                }
            }

            $slots[] = [
                'time' => $slotStart->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'available' => $available,
            ];

            $currentTime->addMinutes(30);
        }

        return $slots;
    }
}