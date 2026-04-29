<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PdfHelper;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\RiwayatHafalanService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $memorizationService;

    public function __construct(RiwayatHafalanService $memorizationService)
    {
        $this->memorizationService = $memorizationService;
    }
    public function index()
    {
        $students = Student::with(['parent', 'guru'])->latest()->get();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $parents = User::where('role', 'orang_tua')->get();
        $gurus = User::where('role', 'guru')->get();

        return view('admin.students.create', compact('parents', 'gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis',
            'parent_id' => 'required|exists:users,id',
            'guru_id' => 'required|exists:users,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ]);

        Student::create($request->all());

        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $parents = User::where('role', 'orang_tua')->get();
        $gurus = User::where('role', 'guru')->get();

        return view('admin.students.edit', compact('student', 'parents', 'gurus'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis,'.$student->id,
            'parent_id' => 'required|exists:users,id',
            'guru_id' => 'required|exists:users,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ]);

        $student->update($request->all());

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
