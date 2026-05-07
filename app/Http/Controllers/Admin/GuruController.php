<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = User::where('role', 'guru')->latest()->get();

        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.unique' => 'Gagal: Email ini sudah terdaftar!',
            'phone.unique' => 'Gagal: Nomor HP ini sudah terdaftar!',
        ]);

        $password = $request->password ? Hash::make($request->password) : Hash::make($request->phone);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => ltrim($request->phone, '0'),
            'password' => $password,
            'role' => 'guru',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    public function edit(User $guru)
    {
        if ($guru->role !== 'guru') {
            abort(403);
        }

        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, User $guru)
    {
        if ($guru->role !== 'guru') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($guru->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($guru->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.unique' => 'Gagal: Email ini sudah terdaftar untuk pengguna lain!',
            'phone.unique' => 'Gagal: Nomor HP ini sudah terdaftar untuk pengguna lain!',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => ltrim($request->phone, '0'),
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $guru->update($data);

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroy(User $guru)
    {
        if ($guru->role !== 'guru') {
            abort(403);
        }
        
        $guru->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil dihapus.');
    }
}
