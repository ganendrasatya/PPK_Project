<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDamageReportRequest;
use App\Models\Facility;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DamageReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $user = Auth::user();

        // Laporan Saya
        $query = Report::with('facility')->where('user_id', $user->id);

        if ($status) {
            $query->where('status', $status);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(10, ['*'], 'my_reports_page');
        $facilities = Facility::where('status', 'aktif')->get();

        // Kelola Laporan Kerusakan (untuk Admin & Petugas)
        $manageStatus = $request->input('manage_status', 'menunggu');
        $manageReports = null;
        $manageCounts = [];

        if ($user->isAdmin() || $user->isPetugas()) {
            $allQuery = Report::with(['facility', 'user']);

            $statusDbMap = [
                'menunggu' => 'baru',
                'diproses' => 'diproses',
                'selesai' => 'selesai',
                'ditolak' => 'ditolak',
            ];

            if ($manageStatus !== 'semua' && isset($statusDbMap[$manageStatus])) {
                $allQuery->where('status', $statusDbMap[$manageStatus]);
            }

            $manageReports = $allQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'manage_page');

            $manageCounts = [
                'menunggu' => Report::where('status', 'baru')->count(),
                'diproses' => Report::where('status', 'diproses')->count(),
                'selesai' => Report::where('status', 'selesai')->count(),
                'ditolak' => Report::where('status', 'ditolak')->count(),
                'semua' => Report::count(),
            ];
        }

        return view('reports.index', compact('reports', 'facilities', 'status', 'manageReports', 'manageCounts', 'manageStatus'));
    }

    public function store(StoreDamageReportRequest $request)
    {
        $validated = $request->validated();
        
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        Report::create([
            'user_id' => Auth::id(),
            'facility_id' => $validated['facility_id'],
            'title' => $validated['title'],
            'category' => $validated['category'],
            'urgency' => $validated['urgency'],
            'description' => $validated['description'],
            'photo_path' => $photoPath,
            'status' => 'baru',
        ]);

        return back()->with('success', 'Laporan kerusakan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Report $report)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isPetugas()) {
            abort(403, 'Aksi ini hanya dapat dilakukan oleh petugas atau admin.');
        }

        $validated = $request->validate([
            'status' => 'required|in:baru,diproses,selesai,ditolak',
            'resolution_note' => ['required_if:status,ditolak', 'nullable', 'string', 'max:255'],
        ]);

        $report->update([
            'status' => $validated['status'],
            'resolution_note' => $validated['resolution_note'] ?? $report->resolution_note,
        ]);

        return back()->with('success', 'Status penanganan laporan berhasil diperbarui menjadi ' . ucfirst($validated['status']) . '.');
    }
}
