<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\RiwayatHafalan;
use App\Helpers\PdfHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StudentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $search = $request->get('search');
        $gender = $request->get('gender');
        $sort   = $request->get('sort', 'latest');
        
        $query = Student::with(['parents', 'targets', 'memorizations'])
            ->where('guru_id', auth()->id());

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($sort === 'abjad') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'nis') {
            $query->orderBy('nis', 'asc');
        } else {
            $query->latest();
        }

        $students = $query->paginate(25)->withQueryString();

        return view('guru.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $this->authorize('view', $student);
        $student->load(['parents', 'targets']);
        
        // Ambil riwayat hafalan secara terpisah agar bisa diurutkan dari yang terbaru
        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        
        return view('guru.students.show', compact('student', 'memorizations'));
    }

    public function create()
    {
        return view('guru.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis',
            'gender' => 'required|in:Laki-laki,Perempuan',
        ]);

        $validated['guru_id'] = auth()->id();
        Student::create($validated);

        return redirect()->route('guru.students.index')->with('success', 'Santri berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $this->authorize('update', $student);
        return view('guru.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'gender' => 'required|in:Laki-laki,Perempuan',
        ]);

        $student->update($validated);
        return redirect()->route('guru.students.index')->with('success', 'Profil santri berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);
        $student->delete();
        return redirect()->route('guru.students.index')->with('success', 'Data santri berhasil dihapus.');
    }

    public function exportPdf(Student $student)
    {
        $this->authorize('view', $student);
        $student->load(['parents', 'guru', 'targets', 'memorizations']);
        
        $memorizations = $student->memorizations()->with('guru')->latest()->get();
        $logoBase64 = PdfHelper::getLogoBase64();

        $pdf = Pdf::loadView('pdf.student_report', compact('student', 'memorizations', 'logoBase64'));
        return $pdf->download('Report_' . $student->nis . '.pdf');
    }
}
