<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAssignment;
use App\Models\User;
use App\Models\RiwayatHafalan;
use App\Models\StudentTarget;
use App\Helpers\PdfHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear  = now()->year;
        $defaultStartYear    = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $defaultAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);

        // Kumpulkan tahun ajaran yang pernah ada untuk Guru ini
        $academicYears = StudentAssignment::where('guru_id', Auth::id())
            ->distinct()
            ->pluck('academic_year')
            ->push($defaultAcademicYear)
            ->unique()
            ->sortByDesc(fn($y) => $y)
            ->values()
            ->toArray();

        $academicYear = $request->input('academic_year', $defaultAcademicYear);
        $search = $request->get('search');
        $gender = $request->get('gender');
        $sort   = $request->get('sort', 'latest');

        $query = Student::with(['parents', 'targets', 'memorizations'])
            ->where('guru_id', Auth::id());

        // Filter per tahun ajaran via student_assignments
        if ($academicYear !== 'all') {
            $query->whereHas('academicAssignments', function ($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($sort === 'abjad') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'nis') {
            $query->orderBy('nis', 'asc');
        } else {
            $query->latest();
        }

        $perPage  = $request->input('per_page', 25);
        $students = $query->paginate($perPage)->withQueryString();

        return view('guru.students.index', compact('students', 'academicYears', 'academicYear'));
    }

    public function show(Request $request, Student $student)
    {
        $this->authorize('view', $student);
        $student->load(['parents', 'targets']);

        $currentMonth = now()->month;
        $currentYear  = now()->year;
        $defaultStartYear    = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $defaultAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);

        // Tahun ajaran yang pernah ada untuk santri ini (berdasarkan guru ybs)
        $academicYears = StudentAssignment::where('student_id', $student->id)
            ->where('guru_id', Auth::id())
            ->distinct()
            ->pluck('academic_year')
            ->push($defaultAcademicYear)
            ->unique()
            ->sortByDesc(fn($y) => $y)
            ->values()
            ->toArray();

        $academicYear = $request->input('academic_year', $defaultAcademicYear);

        // Riwayat hafalan tidak difilter tahun ajaran, melainkan menampilkan seluruhnya
        $memorizations = $student->memorizations()
            ->with('guru')
            ->latest()
            ->get();

        $academicYears = [];

        return view('guru.students.show', compact('student', 'memorizations', 'academicYears', 'academicYear'));
    }

    public function create()
    {
        $parents = User::where('role', 'orang_tua')->orderBy('name')->get();
        return view('guru.students.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|digits:10|unique:students,nis',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'parent_names.*' => 'nullable|string|max:255',
            'parent_phones.*' => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'parent_genders.*' => 'nullable|in:Laki-laki,Perempuan',
            'parent_emails.*' => 'nullable|email|max:255',
            'existing_parent_ids.*' => 'nullable|exists:users,id',
            'target_juz' => 'nullable|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ], [
            'name.required' => 'Nama santri wajib diisi.',
            'gender.required' => 'Jenis kelamin santri wajib dipilih.',
            'nis.required' => 'NISN wajib diisi.',
            'nis.digits' => 'NISN harus berjumlah persis 10 angka.',
            'nis.unique' => 'NISN sudah terdaftar di sistem.',
            'parent_emails.*.email' => 'Format email orang tua tidak valid.',
            'parent_phones.*.regex' => 'Nomor HP orang tua harus berupa angka (10 hingga 15 digit).',
        ]);

        $hasExistingParents = $request->filled('existing_parent_ids') && count(array_filter($request->existing_parent_ids)) > 0;
        $hasNewParents = false;

        if ($request->filled('parent_names')) {
            foreach ($request->parent_names as $pIndex => $pName) {
                if (!empty(trim($pName))) {
                    $hasNewParents = true;
                    $pPhone = $request->parent_phones[$pIndex] ?? '';
                    if (empty(trim($pPhone))) {
                        throw ValidationException::withMessages([
                            "parent_phones.$pIndex" => "Nomor HP wajib diisi untuk data orang tua baru."
                        ]);
                    }
                }
            }
        }

        if (!$hasExistingParents && !$hasNewParents) {
            throw ValidationException::withMessages([
                'parent_names' => 'Orang tua wajib diisi. Silakan pilih orang tua yang sudah ada atau tambahkan data orang tua baru.'
            ]);
        }

        return DB::transaction(function () use ($request) {
            $student = Student::create([
                'name' => $request->name,
                'nis' => $request->nis,
                'gender' => $request->gender,
                'guru_id' => auth()->id(),
            ]);

            // Simpan Target Hafalan
            if ($request->filled('target_juz')) {
                $student->targets()->create([
                    'target_juz' => $request->target_juz,
                    'target_date' => $request->target_date,
                ]);
            }

            $parentIds = [];

            // 1. Proses orang tua yang dipilih dari data yang sudah ada
            if ($request->filled('existing_parent_ids')) {
                foreach ($request->existing_parent_ids as $pid) {
                    if ($pid)
                        $parentIds[] = (int) $pid;
                }
            }

            // 2. Proses form orang tua baru
            foreach (($request->parent_names ?? []) as $index => $parentName) {
                if (empty($parentName))
                    continue;

                $phoneRaw = $request->parent_phones[$index] ?? '';
                $phone = '';

                if ($phoneRaw) {
                    $phone = preg_replace('/[^0-9]/', '', $phoneRaw);
                    if (str_starts_with($phone, '62')) {
                        $phone = '0' . substr($phone, 2);
                    } elseif (str_starts_with($phone, '8')) {
                        $phone = '0' . $phone;
                    }

                    // Cek apakah nomor HP sudah ada di database
                    $existingParent = User::where('phone', $phone)->where('role', 'orang_tua')->first();
                    if ($existingParent) {
                        throw ValidationException::withMessages([
                            "parent_phones.$index" => "Nomor HP '$phone' sudah digunakan oleh '{$existingParent->name}'. Silakan pilih data tersebut di bagian 'Cari Orang Tua'."
                        ]);
                    }
                }

                $email = $request->parent_emails[$index] ?? null;
                $gender = $request->parent_genders[$index] ?? null;

                $parent = User::create([
                    'name' => $parentName,
                    'gender' => $gender,
                    'email' => $email ?: ('ortu_' . Str::slug($parentName) . '_' . Str::random(5) . '@tahfidz.local'),
                    'phone' => $phone,
                    'password' => Hash::make($phone ?: Str::random(10)),
                    'role' => 'orang_tua',
                    'email_verified_at' => now(),
                ]);

                $parentIds[] = $parent->id;
            }

            $student->parents()->sync($parentIds);

            return redirect()->route('guru.students.index')->with('success', 'Santri berhasil ditambahkan. Akun orang tua otomatis dibuat dengan password = nomor telepon.');
        });
    }

    public function edit(Student $student)
    {
        $this->authorize('update', $student);
        return view('guru.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'gender' => 'required|in:Laki-laki,Perempuan',
            'target_juz.*' => 'nullable|integer|min:1|max:30',
            'target_date.*' => 'nullable|date',
        ]);

        $student->update([
            'name' => $request->name,
            'nis' => $request->nis,
            'gender' => $request->gender,
        ]);

        // Update Targets
        if ($request->has('target_juz')) {
            $student->targets()->delete();
            foreach ($request->target_juz as $idx => $juz) {
                if ($juz) {
                    $student->targets()->create([
                        'target_juz' => $juz,
                        'target_date' => $request->target_date[$idx] ?? null,
                    ]);
                }
            }
        }

        $student->refreshCache();
        return redirect()->route('guru.students.index')->with('success', 'Profil santri berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);
        $student->delete();
        return redirect()->route('guru.students.index')->with('success', 'Data santri berhasil dihapus.');
    }

    public function exportPdf(Student $student)
    {
        $this->authorize('view', $student);
        $student->load(['parents', 'guru', 'targets', 'memorizations']);
        
        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        $logoBase64 = PdfHelper::getLogoBase64();

        $pdf = Pdf::loadView('pdf.student_report', compact('student', 'memorizations', 'logoBase64'));
        return $pdf->download('Report_' . $student->nis . '.pdf');
    }
}
