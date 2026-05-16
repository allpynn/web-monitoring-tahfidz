<?php

namespace App\Http\Controllers\Guru;

use App\Helpers\PdfHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $students = Student::with(['parents', 'memorizations'])
            ->where('guru_id', auth()->id())
            ->latest()
            ->get();

        return view('guru.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $this->authorize('view', $student);

        $student->load('parents');
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
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nis' => 'required|string|digits:10|unique:students,nis',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
            'parent_names.*' => 'nullable|string|max:255',
            'parent_phones.*' => 'nullable|string|max:14',
            'parent_genders.*' => 'nullable|in:Laki-laki,Perempuan',
            'parent_emails.*' => 'nullable|email|max:255',
            'existing_parent_ids.*' => 'nullable|exists:users,id',
        ], [
            'name.required' => 'Nama santri wajib diisi.',
            'gender.required' => 'Jenis kelamin santri wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'nis.required' => 'NISN wajib diisi.',
            'nis.digits' => 'NISN harus berjumlah persis 10 angka.',
            'nis.unique' => 'NISN sudah terdaftar di sistem.',
            'target_juz.required' => 'Target juz wajib diisi.',
            'target_juz.min' => 'Target juz minimal 1.',
            'target_juz.max' => 'Target juz maksimal 30.',
            'parent_emails.*.email' => 'Format email orang tua tidak valid.',
            'existing_parent_ids.*.exists' => 'Data orang tua yang dipilih tidak ditemukan.',
        ]);

        $studentData = $request->only(['name', 'gender', 'nis', 'target_juz', 'target_date']);
        $studentData['guru_id'] = auth()->id(); // Otomatis ke guru yang login

        $student = Student::create($studentData);

        $parentIds = [];

        // 1. Proses orang tua yang dipilih dari data yang sudah ada
        if ($request->filled('existing_parent_ids')) {
            foreach ($request->existing_parent_ids as $pid) {
                if ($pid)
                    $parentIds[] = (int) $pid;
            }
        }

        // 2. Validasi & Proses form orang tua baru
        foreach (($request->parent_names ?? []) as $index => $parentName) {
            if (empty($parentName))
                continue;

            $phoneRaw = $request->parent_phones[$index] ?? '';

            if ($phoneRaw) {
                // Normalisasi nomor HP ke format 08...
                $phone = preg_replace('/[^0-9]/', '', $phoneRaw);
                if (str_starts_with($phone, '62')) {
                    $phone = '0' . substr($phone, 2);
                } elseif (str_starts_with($phone, '8')) {
                    $phone = '0' . $phone;
                }

                // Cek apakah nomor HP sudah ada di database
                $existingParent = User::where('phone', $phone)->where('role', 'orang_tua')->first();
                if ($existingParent) {
                    return redirect()->back()->withInput()->withErrors([
                        "parent_phones.$index" => "Nomor HP '$phone' sudah digunakan oleh '{$existingParent->name}'. Silakan pilih data tersebut di bagian 'Cari Orang Tua'."
                    ]);
                }
            }

            $email = $request->parent_emails[$index] ?? null;
            $gender = $request->parent_genders[$index] ?? null;

            // Buat parent baru dengan nomor yang sudah dibersihkan
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

        return redirect()->route('guru.students.index')->with('success', 'Santri berhasil ditambahkan. Akun orang tua otomatis dibuat atau dihubungkan.');
    }

    public function edit(Student $student)
    {
        $this->authorize('update', $student);

        $student->load('parents');
        $parents = User::where('role', 'orang_tua')->get();

        return view('guru.students.edit', compact('student', 'parents'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();
        $parentIds = $data['parent_ids'] ?? [];
        unset($data['parent_ids']);

        $student->update($data);
        $student->parents()->sync($parentIds);

        return redirect()->route('guru.students.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        $student->delete();

        return redirect()->route('guru.students.index')->with('success', 'Santri berhasil dihapus.');
    }

    public function exportPdf(Student $student)
    {
        $this->authorize('view', $student);

        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        $logoBase64 = PdfHelper::getLogoBase64();

        $pdf = Pdf::loadView('pdf.student_report', compact('student', 'memorizations', 'logoBase64'));

        return $pdf->download('Laporan_' . $student->nis . '.pdf');
    }
}
