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
        // Eager load students with relations to prevent N+1 in sidebar/selector
        $students = auth()->user()->students()->with(['parents', 'targets'])->get();
        
        $selectedStudentId = $request->input('student_id', $students->first()?->id);
        $search = $request->input('search');
        $date = $request->input('date');

        $status = $request->input('status');
        $presence = $request->input('presence');

        $student = $students->find($selectedStudentId);

        if (! $student) {
            return view('parent.history.index', [
                'hafalan' => collect(),
                'student' => null,
                'students' => $students,
            ]);
        }

        $query = RiwayatHafalan::with(['guru', 'student']) // Pre-load guru and student
            ->where('student_id', $student->id);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('surah', 'like', "%{$search}%")
                  ->orWhere('ayat', 'like', "%{$search}%")
                  ->orWhere('juz', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($date) {
            $query->whereDate('tanggal', $date);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($presence) {
            $query->where('is_present', $presence === 'hadir');
        }

        $hafalan = $query->latest()->paginate(25)->withQueryString();

        return view('parent.history.index', compact('hafalan', 'student', 'students'));
    }

    public function exportPdf(Student $student)
    {
        // Security check: Allow any parent associated with the student
        if (!$student->parents->contains(auth()->id())) {
            abort(403);
        }

        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        $logoBase64 = PdfHelper::getLogoBase64();

        $pdf = Pdf::loadView('pdf.student_report', compact('student', 'memorizations', 'logoBase64'));

        return $pdf->download('Riwayat_Hafalan_'.$student->nis.'.pdf');
    }
}
