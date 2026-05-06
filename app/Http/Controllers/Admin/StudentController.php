<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PdfHelper;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\RiwayatHafalanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    protected $memorizationService;

    public function __construct(RiwayatHafalanService $memorizationService)
    {
        $this->memorizationService = $memorizationService;
    }
    public function index()
    {
        $students = Student::with(['parents', 'guru'])->latest()->get();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $gurus = User::where('role', 'guru')->get();

        return view('admin.students.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|digits:10|unique:students,nis',
            'parent_names' => 'required|array|min:1',
            'parent_names.*' => 'required|string|max:255',
            'parent_phones' => 'required|array|min:1',
            'parent_phones.*' => 'required|string|max:20',
            'guru_id' => 'required|exists:users,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ], [
            'nis.unique' => 'Gagal: Data ini sudah ada! NISN tersebut sudah terdaftar di dalam sistem.',
            'nis.digits' => 'Gagal: NISN harus berjumlah persis 10 angka.',
        ]);

        $student = Student::create($request->only(['name', 'nis', 'guru_id', 'target_juz', 'target_date']));

        // Buat akun orang tua otomatis dari input manual
        $parentIds = [];
        foreach ($request->parent_names as $index => $parentName) {
            $phone = $request->parent_phones[$index] ?? '';

            // Cek apakah orang tua sudah ada berdasarkan nomor telepon
            $parent = User::where('phone', $phone)->where('role', 'orang_tua')->first();

            if (!$parent) {
                // Buat akun baru dengan password default dari nomor telepon
                $parent = User::create([
                    'name' => $parentName,
                    'email' => 'parent_' . Str::slug($parentName) . '_' . Str::random(4) . '@tahfidz.local',
                    'phone' => $phone,
                    'password' => Hash::make($phone),
                    'role' => 'orang_tua',
                ]);
            }

            $parentIds[] = $parent->id;
        }

        $student->parents()->sync($parentIds);

        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil ditambahkan. Akun orang tua otomatis dibuat dengan password = nomor telepon.');
    }

    public function edit(Student $student)
    {
        $student->load('parents');
        $parents = User::where('role', 'orang_tua')->get();
        $gurus = User::where('role', 'guru')->get();

        return view('admin.students.edit', compact('student', 'parents', 'gurus'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|digits:10|unique:students,nis,'.$student->id,
            'parent_ids' => 'required|array',
            'parent_ids.*' => 'exists:users,id',
            'guru_id' => 'required|exists:users,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ], [
            'nis.unique' => 'Gagal: Data ini sudah ada! NISN tersebut sudah terdaftar di dalam sistem.',
            'nis.digits' => 'Gagal: NISN harus berjumlah persis 10 angka.',
        ]);

        $student->update($request->only(['name', 'nis', 'guru_id', 'target_juz', 'target_date']));
        $student->parents()->sync($request->parent_ids);

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

        return $pdf->download('Laporan_'.$student->nis.'.pdf');
    }
}
