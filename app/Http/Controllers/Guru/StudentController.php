<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['parent', 'memorizations'])
            ->where('guru_id', auth()->id())
            ->latest()
            ->get();

        return view('guru.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        if ($student->guru_id !== auth()->id()) {
            abort(403);
        }

        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        return view('guru.students.show', compact('student', 'memorizations'));
    }

    public function create()
    {
        $parents = \App\Models\User::where('role', 'orang_tua')->get();
        return view('guru.students.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis',
            'parent_id' => 'required|exists:users,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['guru_id'] = auth()->id();

        Student::create($data);

        return redirect()->route('guru.students.index')->with('success', 'Santri berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        if ($student->guru_id !== auth()->id()) {
            abort(403);
        }
        $parents = \App\Models\User::where('role', 'orang_tua')->get();
        return view('guru.students.edit', compact('student', 'parents'));
    }

    public function update(Request $request, Student $student)
    {
        if ($student->guru_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'parent_id' => 'required|exists:users,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ]);

        $student->update($request->all());

        return redirect()->route('guru.students.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        if ($student->guru_id !== auth()->id()) {
            abort(403);
        }
        $student->delete();
        return redirect()->route('guru.students.index')->with('success', 'Santri berhasil dihapus.');
    }

    public function exportPdf(Student $student)
    {
        if ($student->guru_id !== auth()->id()) {
            abort(403);
        }

        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        $logoBase64 = \App\Helpers\PdfHelper::getLogoBase64();
        
        $pdf = Pdf::loadView('pdf.student_report', compact('student', 'memorizations', 'logoBase64'));
        return $pdf->download('Laporan_' . $student->nis . '.pdf');
    }
}
