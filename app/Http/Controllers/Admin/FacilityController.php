<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFacilityRequest;
use App\Http\Requests\Admin\UpdateFacilityRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::latest()->paginate(15);

        return view('admin.facilities.index', ['facilities' => $facilities]);
    }

    public function create(): View
    {
        return view('admin.facilities.create');
    }

    public function store(StoreFacilityRequest $request): RedirectResponse
    {
        Facility::create($request->validated());

        return redirect()->route('admin.facilities.index')->with('status', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Facility $facility): View
    {
        return view('admin.facilities.edit', ['facility' => $facility]);
    }

    public function update(UpdateFacilityRequest $request, Facility $facility): RedirectResponse
    {
        $facility->update($request->validated());

        return redirect()->route('admin.facilities.index')->with('status', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
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
