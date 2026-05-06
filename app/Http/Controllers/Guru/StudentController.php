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
        $parents = User::where('role', 'orang_tua')->get();

        return view('guru.students.create', compact('parents'));
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();
        $data['guru_id'] = auth()->id();

        $parentIds = $data['parent_ids'] ?? [];
        unset($data['parent_ids']);

        $student = Student::create($data);
        $student->parents()->sync($parentIds);

        return redirect()->route('guru.students.index')->with('success', 'Santri berhasil ditambahkan.');
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

        return $pdf->download('Laporan_'.$student->nis.'.pdf');
    }
}
