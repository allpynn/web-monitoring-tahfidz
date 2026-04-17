<?php

namespace App\Http\Controllers\Guru;

use App\Helpers\PdfHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHafalanRequest;
use App\Http\Requests\UpdateHafalanRequest;
use App\Models\Memorization;
use App\Models\Student;
use App\Models\Surah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class HafalanController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $hafalan = Memorization::with('student')
            ->where('guru_id', Auth::id())
            ->latest()
            ->get();

        return view('guru.hafalan.index', compact('hafalan'));
    }

    public function create()
    {
        $students = Student::where('guru_id', Auth::id())->get();
        $surahsList = Surah::orderBy('nomor');

        return view('guru.hafalan.create', compact('students', 'surahsList'));
    }

    public function store(StoreHafalanRequest $request)
    {
        $data = $request->validated();
        $data['guru_id'] = Auth::id();

        if (! $request->is_present) {
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
        $this->authorize('update', $hafalan);

        $students = Student::where('guru_id', Auth::id())->get();
        $surahsList = Surah::orderBy('nomor');

        return view('guru.hafalan.edit', compact('hafalan', 'students', 'surahsList'));
    }

    public function update(UpdateHafalanRequest $request, Memorization $hafalan)
    {
        $data = $request->validated();

        if (! $request->is_present) {
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
        $this->authorize('delete', $hafalan);

        $hafalan->delete();

        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil dihapus.');
    }

    public function exportPdf(Student $student)
    {
        $this->authorize('view', $student);

        $memorizations = $student->memorizations()->with('guru')->latest()->take(20)->get();
        $logoBase64 = PdfHelper::getLogoBase64();

        $pdf = Pdf::loadView('pdf.student_report', compact('student', 'memorizations', 'logoBase64'));

        return $pdf->download('Raport_Tahfidz_'.$student->nis.'.pdf');
    }

    public function exportSemesterPdf(Student $student)
    {
        $this->authorize('view', $student);

        $memorizations = $student->memorizations()
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at', 'asc')
            ->get();

        $logoBase64 = PdfHelper::getLogoBase64();

        $pdf = Pdf::loadView('pdf.semester_recap', compact('student', 'memorizations', 'logoBase64'));

        return $pdf->download('Rekap_Semester_'.$student->nis.'.pdf');
    }
}
