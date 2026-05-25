<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'guru');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->sort === 'abjad') {
            $query->orderBy('name', 'asc');
        } elseif ($request->sort === 'nip') {
            $query->orderBy('nip', 'asc');
        } else {
            $query->latest();
        }

        $perPage = $request->input('per_page', 25);
        $gurus = $query->paginate($perPage)->withQueryString();

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
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nip' => 'required|digits:18|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.unique' => 'Gagal: Email ini sudah terdaftar!',
            'phone.unique' => 'Gagal: Nomor HP ini sudah terdaftar!',
            'nip.required' => 'Gagal: NIP wajib diisi!',
            'nip.unique'   => 'Gagal: NIP ini sudah terdaftar untuk pengguna lain!',
            'nip.digits'   => 'Gagal: NIP harus bersisi persis 18 angka!',
        ]);

        $phone = $request->phone;
        $password = $request->password ? Hash::make($request->password) : Hash::make($phone);

        User::create([
            'name'               => $request->name,
            'gender'             => $request->gender,
            'nip'                => $request->nip,
            'email'              => $request->email,
            'phone'              => $phone,
            'password'           => $password,
            'role'               => 'guru',
            'email_verified_at'  => now(),
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
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nip' => ['required', 'digits:18', Rule::unique('users')->ignore($guru->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($guru->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($guru->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.unique' => 'Gagal: Email ini sudah terdaftar!',
            'phone.unique' => 'Gagal: Nomor HP ini sudah terdaftar!',
            'nip.required' => 'Gagal: NIP wajib diisi!',
            'nip.unique'   => 'Gagal: NIP ini sudah terdaftar untuk pengguna lain!',
            'nip.digits'   => 'Gagal: NIP harus bersisi persis 18 angka!',
        ]);

        $data = [
            'name'   => $request->name,
            'gender' => $request->gender,
            'nip'    => $request->nip,
            'email'  => $request->email,
            'phone'  => $request->phone,
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
