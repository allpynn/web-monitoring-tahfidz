<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = \App\Models\Student::with(['parent', 'guru'])->latest()->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $parents = \App\Models\User::where('role', 'orang_tua')->get();
        $gurus = \App\Models\User::where('role', 'guru')->get();
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

        \App\Models\Student::create($request->all());

        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil ditambahkan.');
    }
    public function edit(\App\Models\Student $student)
    {
        $parents = \App\Models\User::where('role', 'orang_tua')->get();
        $gurus = \App\Models\User::where('role', 'guru')->get();
        return view('admin.students.edit', compact('student', 'parents', 'gurus'));
    }

    public function update(Request $request, \App\Models\Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'parent_id' => 'required|exists:users,id',
            'guru_id' => 'required|exists:users,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ]);

        $student->update($request->all());

        return redirect()->route('admin.students.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(\App\Models\Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil dihapus.');
    }

    public function exportPdf(\App\Models\Student $student)
    {
        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.student_report', compact('student', 'memorizations'));
        return $pdf->download('Laporan_' . $student->nis . '.pdf');
    }
}
