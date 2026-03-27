<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Memorization;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class HafalanController extends Controller
{
    public function index()
    {
        $hafalan = Memorization::with('student')->where('guru_id', auth()->id())->latest()->get();
        return view('guru.hafalan.index', compact('hafalan'));
    }

    public function create()
    {
        $students = Student::all();
        return view('guru.hafalan.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'is_present' => 'required|boolean',
            'juz' => 'required_if:is_present,1|nullable|integer|min:1|max:30',
            'surah' => 'required_if:is_present,1|nullable|string',
            'ayat' => 'required_if:is_present,1|nullable|string',
            'status' => 'required_if:is_present,1|nullable|in:Lancar,Perlu Perbaikan',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['guru_id'] = auth()->id();

        if (!$request->is_present) {
            $data['juz'] = null;
            $data['surah'] = null;
            $data['ayat'] = null;
            $data['status'] = null;
        }

        Memorization::create($data);

        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil disimpan.');
    }

    public function edit(Memorization $hafalan)
    {
        $this->authorizeGuru($hafalan);
        $students = Student::all();
        return view('guru.hafalan.edit', compact('hafalan', 'students'));
    }

    public function update(Request $request, Memorization $hafalan)
    {
        $this->authorizeGuru($hafalan);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'is_present' => 'required|boolean',
            'juz' => 'required_if:is_present,1|nullable|integer|min:1|max:30',
            'surah' => 'required_if:is_present,1|nullable|string',
            'ayat' => 'required_if:is_present,1|nullable|string',
            'status' => 'required_if:is_present,1|nullable|in:Lancar,Perlu Perbaikan',
            'notes' => 'nullable|string',
            'parent_comment' => 'nullable|string',
        ]);

        $data = $request->all();
        if (!$request->is_present) {
            $data['juz'] = null;
            $data['surah'] = null;
            $data['ayat'] = null;
            $data['status'] = null;
        }

        $hafalan->update($data);

        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Memorization $hafalan)
    {
        $this->authorizeGuru($hafalan);
        $hafalan->delete();
        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil dihapus.');
    }

    public function exportPdf(Student $student)
    {
        $memorizations = $student->memorizations()->with('guru')->latest()->take(20)->get();
        $current_juz = $student->memorizations()->where('is_present', true)->latest()->first()?->juz ?? 0;

        $pdf = Pdf::loadView('pdf.student_report', compact('student', 'memorizations', 'current_juz'));
        
        return $pdf->download('Raport_Tahfidz_' . $student->nis . '.pdf');
    }

    protected function authorizeGuru(Memorization $hafalan)
    {
        if ($hafalan->guru_id !== auth()->id()) {
            abort(403);
        }
    }
}
