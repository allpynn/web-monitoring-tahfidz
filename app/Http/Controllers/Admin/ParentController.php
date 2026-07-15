<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $defaultAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);

        $academicYears = \App\Models\StudentAssignment::distinct()
            ->pluck('academic_year')
            ->push($defaultAcademicYear)
            ->unique()
            ->sortByDesc(fn($year) => $year)
            ->values()
            ->toArray();

        $academicYear = $request->input('academic_year', $defaultAcademicYear);

        $query = User::where('role', 'orang_tua');

        if ($academicYear !== 'all') {
            $query->whereHas('students.academicAssignments', function($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            })->withCount(['students' => function($q) use ($academicYear) {
                $q->whereHas('academicAssignments', function($q2) use ($academicYear) {
                    $q2->where('academic_year', $academicYear);
                });
            }]);
        } else {
            $query->withCount('students');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->sort === 'abjad') {
            $query->orderBy('name', 'asc');
        } elseif ($request->sort === 'anak_terbanyak') {
            $query->orderBy('students_count', 'desc');
        } else {
            $query->latest();
        }

        $perPage = $request->input('per_page', 25);
        $parents = $query->paginate($perPage)->withQueryString();

        return view('admin.parents.index', compact('parents', 'academicYears', 'academicYear'));
    }

    public function create()
    {
        return view('admin.parents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.unique' => 'Gagal: Email ini sudah terdaftar di sistem!',
            'phone.unique' => 'Gagal: Nomor HP ini sudah terdaftar di sistem (digunakan oleh akun lain)!',
        ]);

        $phone = $request->phone;
        $password = $request->password ? Hash::make($request->password) : Hash::make($phone);

        User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $phone,
            'password' => $password,
            'role' => 'orang_tua',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.parents.index')->with('success', 'Akun orang tua berhasil dibuat.');
    }

    public function edit(User $parent)
    {
        if ($parent->role !== 'orang_tua') {
            abort(403);
        }

        return view('admin.parents.edit', compact('parent'));
    }

    public function update(Request $request, User $parent)
    {
        if ($parent->role !== 'orang_tua') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($parent->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($parent->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.unique' => 'Gagal: Email ini sudah terdaftar untuk pengguna lain!',
            'phone.unique' => 'Gagal: Nomor HP ini sudah terdaftar untuk pengguna lain!',
        ]);

        $data = [
            'name' => $request->name,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $parent->update($data);

        return redirect()->route('admin.parents.index')->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy(User $parent)
    {
        if ($parent->role !== 'orang_tua') {
            abort(403);
        }
        $parent->delete();

        return redirect()->route('admin.parents.index')->with('success', 'Akun orang tua berhasil dihapus.');
    }
}
