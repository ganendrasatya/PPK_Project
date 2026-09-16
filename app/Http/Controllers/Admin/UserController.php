<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePenggunaRequest;
use App\Http\Requests\Admin\StorePetugasRequest;
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
        $user->update(['status' => 'verified']);

        return back()->with('status', "Akun {$user->name} berhasil diverifikasi.");
    }

    public function reject(User $user): RedirectResponse
    {
        $user->update(['status' => 'rejected']);

        return back()->with('status', "Akun {$user->name} ditolak.");
    }

    public function createPetugas(): View
    {
        return view('admin.users.create-petugas');
    }

    public function storePetugas(StorePetugasRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
            'status' => 'verified',
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Akun petugas berhasil dibuat.');
    }

    public function createPengguna(): View
    {
        return view('admin.users.create-pengguna');
    }

    public function storePengguna(StorePenggunaRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pengguna',
            'status' => 'verified',
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Akun pengguna berhasil dibuat.');
    }
}
