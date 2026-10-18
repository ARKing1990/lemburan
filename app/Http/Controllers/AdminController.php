<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lembur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    // --- 1. MANAJEMEN USER ---

    public function indexUser()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil dibuat');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
    }

    // --- 2. MONITORING LEMBUR ---

    public function indexLembur(Request $request)
    {
        // Ambil list semua user untuk filter dropdown
        $users = User::all();

        // Ambil input filter
        $userId = $request->input('user_id');
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));

        // Hitung range tanggal (26 bulan lalu - 25 bulan ini)
        $date = Carbon::createFromFormat('Y-m', $bulan);
        $startDate = $date->copy()->subMonth()->day(26)->startOfDay();
        $endDate = $date->copy()->day(25)->endOfDay();

        // Query Data
        $query = Lembur::with('user') // Eager load user agar efisien
                       ->whereBetween('tanggal', [$startDate, $endDate])
                       ->orderBy('tanggal', 'desc');

        // Jika ada filter user, tambahkan kondisi
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $dataLembur = $query->get();
        $totalJam = $dataLembur->sum('jumlah_jam');

        return view('admin.lembur.index', compact('dataLembur', 'users', 'totalJam', 'bulan', 'userId'));
    }
}
