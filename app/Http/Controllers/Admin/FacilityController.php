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

        return view('admin.facilities.index', [
            'facilities' => $facilities,
            'statusCounts' => [
                'semua' => Facility::count(),
                'aktif' => Facility::where('status', 'aktif')->count(),
                'nonaktif' => Facility::where('status', 'nonaktif')->count(),
                'dalam_perbaikan' => Facility::where('status', 'dalam_perbaikan')->count(),
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

        return redirect()->route('admin.facilities.index')->with('status', 'Fasilitas berhasil diperbarui.');
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

        $facility->update(['status' => $request->string('status')]);

        return back()->with('status', "Status fasilitas {$facility->nama_fasilitas} berhasil diperbarui.");
    }
}
