<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('name', 'like', '%' . $request->search . '%')
                   ->orWhere('email', 'like', '%' . $request->search . '%');
            }))
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', ['users' => $users]);
    }

    public function pending(): View
    {
        $users = User::where('status', 'pending')->latest()->paginate(15);

        return view('admin.users.pending', ['users' => $users]);
    }

    public function approve(User $user): RedirectResponse
    {
        if ($user->isAdmin() || $user->is(auth()->user())) {
            return back()->with('error', 'Akun admin tidak dapat diubah statusnya.');
        }

        $user->update(['status' => 'verified']);

        return back()->with('status', "Akun {$user->name} berhasil diverifikasi.");
    }

    public function reject(User $user): RedirectResponse
    {
        if ($user->isAdmin() || $user->is(auth()->user())) {
            return back()->with('error', 'Akun admin tidak dapat diubah statusnya.');
        }

        $user->update(['status' => 'rejected']);

        return back()->with('status', "Akun {$user->name} ditolak.");
    }

    public function create(Request $request): View
    {
        $role = in_array($request->query('role'), ['petugas', 'pengguna']) ? $request->query('role') : 'pengguna';

        return view('admin.users.create', compact('role'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $role = $request->validated('role');

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'status' => 'verified',
        ]);

        return redirect()->route('admin.users.index')->with('status', "Akun {$role} berhasil dibuat.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->isAdmin() || $user->is(auth()->user())) {
            return back()->with('error', 'Akun admin atau akun sendiri tidak dapat dihapus.');
        }

        if ($user->reservations()->exists() || $user->reports()->exists()) {
            return back()->with('error', "Akun {$user->name} memiliki riwayat reservasi atau laporan dan tidak dapat dihapus. Nonaktifkan saja dengan menolak statusnya.");
        }

        $user->delete();

        return back()->with('status', "Akun {$user->name} berhasil dihapus dari sistem.");
    }
}
