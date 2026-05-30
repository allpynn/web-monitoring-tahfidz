<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\RiwayatHafalan;
use App\Models\StudentTarget;
use App\Helpers\PdfHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $search = $request->get('search');
        $gender = $request->get('gender');
        $sort   = $request->get('sort', 'latest');
        
        $query = Student::with(['parents', 'targets', 'memorizations'])
            ->where('guru_id', auth()->id());

        if ($search) {
            $query->where(function($q) use ($search) {
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

        $perPage = $request->input('per_page', 25);
        $students = $query->paginate($perPage)->withQueryString();

        return view('guru.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $this->authorize('view', $student);
        $student->load(['parents', 'targets']);
        
        // Ambil riwayat hafalan secara terpisah agar bisa diurutkan dari yang terbaru
        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        
        return view('guru.students.show', compact('student', 'memorizations'));
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
            'parent_phones.*' => 'nullable|string|max:20',
            'parent_genders.*' => 'nullable|in:Laki-laki,Perempuan',
            'parent_emails.*' => 'nullable|email|max:255',
            'existing_parent_ids.*' => 'nullable|exists:users,id',
            'target_juz' => 'nullable|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ]);

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
