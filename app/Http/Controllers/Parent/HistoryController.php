<?php

namespace App\Http\Controllers\Parent;

use App\Helpers\PdfHelper;
use App\Http\Controllers\Controller;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $students = auth()->user()->students;
        $selectedStudentId = $request->get('student_id', $students->first()?->id);

        $student = $students->find($selectedStudentId);

        if (! $student) {
            return view('parent.history.index', [
                'hafalan' => collect(),
                'student' => null,
                'students' => $students,
            ]);
        }

        $hafalan = RiwayatHafalan::with('guru')
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(15);

        return view('parent.history.index', compact('hafalan', 'student', 'students'));
    }

    public function exportPdf(Student $student)
    {
        // Security check
        if ($student->parent_id !== auth()->id()) {
            abort(403);
        }

        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        $logoBase64 = PdfHelper::getLogoBase64();

        $pdf = Pdf::loadView('pdf.student_report', compact('student', 'memorizations', 'logoBase64'));

        return $pdf->download('Riwayat_Hafalan_'.$student->nis.'.pdf');
    }
}
