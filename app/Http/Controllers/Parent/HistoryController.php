<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        // Get students linked to this parent
        $students = auth()->user()->students;
        
        // For simplicity, we take the first student in this demo
        $student = $students->first();
        
        if (!$student) {
            return view('parent.history.index', ['hafalan' => collect(), 'student' => null]);
        }

        $hafalan = \App\Models\Memorization::with('guru')
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        return view('parent.history.index', compact('hafalan', 'student'));
    }
}
