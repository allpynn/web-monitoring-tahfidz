<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $students = auth()->user()->students;
        $selectedStudentId = $request->get('student_id', $students->first()?->id);
        
        $student = $students->find($selectedStudentId);
        
        if (!$student) {
            return view('parent.history.index', [
                'hafalan' => collect(), 
                'student' => null, 
                'students' => $students
            ]);
        }

        $hafalan = \App\Models\Memorization::with('guru')
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(15);

        return view('parent.history.index', compact('hafalan', 'student', 'students'));
    }

    public function exportPdf(\App\Models\Student $student)
    {
        // Security check
        if ($student->parent_id !== auth()->id()) {
            abort(403);
        }

        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.student_report', compact('student', 'memorizations'));
        
        return $pdf->download('Riwayat_Hafalan_' . $student->nis . '.pdf');
    }
}
