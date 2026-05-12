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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return view('guru.students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();
        $validated['guru_id'] = auth()->id();

        // Remove parent_names / parent_phones from student data
        unset($validated['parent_names'], $validated['parent_phones'], $validated['parent_ids']);

        $student = Student::create($validated);

        // Create or find parent accounts based on phone number
        $parentIds = [];
        foreach ($request->parent_names as $index => $parentName) {
            $phone = ltrim($request->parent_phones[$index] ?? '', '0');

            $parent = User::where('phone', $phone)->where('role', 'orang_tua')->first();

            if (!$parent) {
                $parent = User::create([
                    'name' => $parentName,
                    'email' => 'parent_' . Str::slug($parentName) . '_' . Str::random(4) . '@tahfidz.local',
                    'phone' => $phone,
                    'password' => Hash::make($phone),
                    'role' => 'orang_tua',
                    'email_verified_at' => now(),
                ]);
            }

            $parentIds[] = $parent->id;
        }

        $student->parents()->sync($parentIds);

        return redirect()->route('guru.students.index')->with('success', 'Santri berhasil ditambahkan. Akun orang tua otomatis dibuat.');
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
