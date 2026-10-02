<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFacilityRequest;
use App\Http\Requests\Admin\UpdateFacilityRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index(Request $request): View
    {
        $facilities = Facility::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $counts = Facility::toBase()->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');

        return view('admin.facilities.index', [
            'facilities' => $facilities,
            'statusCounts' => [
                'semua' => $counts->sum(),
                'aktif' => $counts['aktif'] ?? 0,
                'nonaktif' => $counts['nonaktif'] ?? 0,
                'dalam_perbaikan' => $counts['dalam_perbaikan'] ?? 0,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.facilities.create');
    }

    public function store(StoreFacilityRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('facilities', 'public');
        }

        Facility::create($data);

        return redirect()->route('admin.facilities.index')->with('status', 'Fasilitas berhasil ditambahkan.');
    }

    public function update(UpdateFacilityRequest $request, Facility $facility): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('facilities', 'public');
            if ($facility->image_path) {
                Storage::disk('public')->delete($facility->image_path);
            }
            $data['image_path'] = $newPath;
        }

        $facility->update($data);

        $message = 'Fasilitas berhasil diperbarui.';
        if ($facility->wasChanged('status') && ($cancelled = $facility->cancelUpcomingReservations()) > 0) {
            $message .= " {$cancelled} reservasi mendatang dibatalkan otomatis.";
        }

        return redirect()->route('admin.facilities.index')->with('status', $message);
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        if ($facility->reservations()->exists() || $facility->reports()->exists()) {
            return back()->with('error', 'Fasilitas punya riwayat reservasi atau laporan dan tidak dapat dihapus. Nonaktifkan saja.');
        }

        if ($facility->image_path) {
            Storage::disk('public')->delete($facility->image_path);
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('status', 'Fasilitas berhasil dihapus.');
    }

    public function updateStatus(Request $request, Facility $facility): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:aktif,nonaktif,dalam_perbaikan'],
        ]);

        $facility->update(['status' => $request->input('status')]);

        $message = "Status fasilitas {$facility->nama_fasilitas} berhasil diperbarui menjadi {$facility->status_label}.";
        if ($facility->wasChanged('status') && ($cancelled = $facility->cancelUpcomingReservations()) > 0) {
            $message .= " {$cancelled} reservasi mendatang dibatalkan otomatis.";
        }

        return back()->with('status', $message)->with('success', $message);
    }
}

