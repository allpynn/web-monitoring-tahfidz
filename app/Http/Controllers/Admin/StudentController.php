<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PdfHelper;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAssignment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\RiwayatHafalanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    protected $memorizationService;

    public function __construct(RiwayatHafalanService $memorizationService)
    {
        $this->memorizationService = $memorizationService;
    }
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

        $query = Student::with(['parents', 'guru', 'targets', 'memorizations']);

        if ($academicYear !== 'all') {
            $query->whereHas('academicAssignments', function($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->sort === 'abjad') {
            $query->orderBy('name', 'asc');
        } elseif ($request->sort === 'nis') {
            $query->orderBy('nis', 'asc');
        } else {
            $query->latest();
        }

        $perPage = $request->input('per_page', 25);
        $students = $query->paginate($perPage)->withQueryString();

        return view('admin.students.index', compact('students', 'academicYears', 'academicYear'));
    }

    public function create()
    {
        $gurus = User::where('role', 'guru')->get();
        $parents = User::where('role', 'orang_tua')->orderBy('name')->get();

        return view('admin.students.create', compact('gurus', 'parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nis' => 'required|string|digits:10|unique:students,nis',
            'guru_id' => 'required|exists:users,id',
            'parent_names.*' => 'nullable|string|max:255',
            'parent_phones.*' => 'nullable|string|max:20',
            'parent_genders.*' => 'nullable|in:Laki-laki,Perempuan',
            'parent_emails.*' => 'nullable|email|max:255',
            'existing_parent_ids.*' => 'nullable|exists:users,id',
            'target_juz.*' => 'nullable|integer|min:1|max:30',
            'target_date.*' => 'nullable|date',
        ], [
            'name.required' => 'Nama santri wajib diisi.',
            'gender.required' => 'Jenis kelamin santri wajib dipilih.',
            'nis.required' => 'NISN wajib diisi.',
            'nis.digits' => 'NISN harus berjumlah persis 10 angka.',
            'nis.unique' => 'NISN sudah terdaftar di sistem.',
            'guru_id.required' => 'Guru pendamping wajib dipilih.',
            'guru_id.exists' => 'Guru yang dipilih tidak valid.',
            'parent_emails.*.email' => 'Format email orang tua tidak valid.',
        ]);

        return DB::transaction(function () use ($request) {
            $student = Student::create($request->only(['name', 'gender', 'nis', 'guru_id']));

            // Otomatis tempatkan santri baru di Tahun Ajaran aktif sekarang
            $currentMonth = now()->month;
            $currentYear = now()->year;
            $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
            $currentAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);

            if ($student->guru_id) {
                StudentAssignment::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'academic_year' => $currentAcademicYear,
                    ],
                    [
                        'guru_id' => $student->guru_id,
                    ]
                );
            }

            if ($request->has('target_juz')) {
                foreach ($request->target_juz as $idx => $juz) {
                    if ($juz) {
                        $student->targets()->create([
                            'target_juz' => $juz,
                            'target_date' => $request->target_date[$idx] ?? null,
                        ]);
                    }
                }
            }

            $parentIds = [];

            if ($request->filled('existing_parent_ids')) {
                foreach ($request->existing_parent_ids as $pid) {
                    if ($pid)
                        $parentIds[] = (int) $pid;
                }
            }

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

            return redirect()->route('admin.students.index')->with('success', 'Santri berhasil ditambahkan. Akun orang tua otomatis dibuat dengan password = nomor telepon.');
        });
    }

    public function edit(Student $student)
    {
        $student->load(['parents', 'targets']);
        $parents = User::where('role', 'orang_tua')->orderBy('name')->get();
        $gurus = User::where('role', 'guru')->orderBy('name')->get();

        return view('admin.students.edit', compact('student', 'parents', 'gurus'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nis' => 'required|string|digits:10|unique:students,nis,' . $student->id,
            'guru_id' => 'required|exists:users,id',
            'parent_names.*' => 'nullable|string|max:255',
            'parent_phones.*' => 'nullable|string|max:20',
            'parent_genders.*' => 'nullable|in:Laki-laki,Perempuan',
            'parent_emails.*' => 'nullable|email|max:255',
            'existing_parent_ids.*' => 'nullable|exists:users,id',
            'target_juz.*' => 'nullable|integer|min:1|max:30',
            'target_date.*' => 'nullable|date',
        ], [
            'name.required' => 'Nama santri wajib diisi.',
            'nis.required' => 'NISN wajib diisi.',
            'nis.unique' => 'NISN sudah terdaftar di sistem.',
        ]);

        $student->update($request->only(['name', 'gender', 'nis', 'guru_id']));

        // Otomatis sinkronkan penugasan di Tahun Ajaran aktif sekarang
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $currentAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);

        if ($student->guru_id) {
            StudentAssignment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_year' => $currentAcademicYear,
                ],
                [
                    'guru_id' => $student->guru_id,
                ]
            );
        }

        $student->targets()->delete();
        if ($request->has('target_juz')) {
            foreach ($request->target_juz as $idx => $juz) {
                if ($juz) {
                    $student->targets()->create([
                        'target_juz' => $juz,
                        'target_date' => $request->target_date[$idx] ?? null,
                    ]);
                }
            }
        }

        $parentIds = [];

        // 1. Proses orang tua yang dipilih dari data yang sudah ada
        if ($request->filled('existing_parent_ids')) {
            foreach ($request->existing_parent_ids as $pid) {
                if ($pid)
                    $parentIds[] = (int) $pid;
            }
        }

        foreach (($request->parent_names ?? []) as $index => $parentName) {
            if (empty($parentName))
                continue;

            $phoneRaw = $request->parent_phones[$index] ?? '';
            $phone = '';

            if ($phoneRaw) {
                $phone = preg_replace('/[^0-9]/', '', $phoneRaw);
                if (str_starts_with($phone, '8'))
                    $phone = '0' . $phone;
                elseif (str_starts_with($phone, '628'))
                    $phone = '0' . substr($phone, 2);

                $existingParent = User::where('phone', $phone)->where('role', 'orang_tua')->first();
                if ($existingParent) {
                    return redirect()->back()->withInput()->withErrors([
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

        return redirect()->route('admin.students.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil dihapus.');
    }

    public function exportPdf(Student $student)
    {
        $pdf = $this->memorizationService->generateStudentReport($student);

        return $pdf->download('Laporan_' . $student->nis . '.pdf');
    }
}
