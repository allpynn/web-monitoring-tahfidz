<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreHafalanRequest;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\StudentAssignment;
use App\Models\Surah;
use Carbon\Carbon;

use App\Services\RiwayatHafalanService;
use App\Events\HafalanUpdated;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class HafalanController extends Controller
{
    use AuthorizesRequests;

    protected $memorizationService;

    public function __construct(RiwayatHafalanService $memorizationService)
    {
        $this->memorizationService = $memorizationService;
    }

    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $defaultAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);

        // Kumpulkan tahun ajaran dari santri yang pernah diampu guru ini
        $academicYears = StudentAssignment::where('guru_id', Auth::id())
            ->distinct()
            ->pluck('academic_year')
            ->push($defaultAcademicYear)
            ->unique()
            ->sortByDesc(fn($y) => $y)
            ->values()
            ->toArray();

        $academicYear = $request->input('academic_year', $defaultAcademicYear);
        $perPage = $request->input('per_page', 25);
        $search = $request->input('search');
        $date = $request->input('date');
        $status = $request->input('status');
        $presence = $request->input('presence');

        $query = RiwayatHafalan::with('student')
            ->where('guru_id', Auth::id());

        // Filter per tahun ajaran: hanya santri yang diampu tahun ini, di rentang Juli–Juni
        if ($academicYear !== 'all') {
            $years = explode('/', $academicYear);
            $baseYear = (int) ($years[0] ?? $defaultStartYear);
            $startDate = Carbon::create($baseYear, 7, 1)->startOfDay();
            $endDate = Carbon::create($baseYear + 1, 6, 30)->endOfDay();

            // Hanya santri yang punya assignment di tahun ini
            $assignedStudentIds = StudentAssignment::where('guru_id', Auth::id())
                ->where('academic_year', $academicYear)
                ->pluck('student_id');

            $query->whereIn('student_id', $assignedStudentIds)
                ->whereBetween('tanggal', [$startDate, $endDate]);
        }

        if ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
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

        $hafalan = $query->latest()->paginate($perPage)->withQueryString();

        return view('guru.hafalan.index', compact('hafalan', 'academicYears', 'academicYear'));
    }

    public function create()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $currentAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);

        // Ambil ID santri yang diampu guru ini pada tahun ajaran sekarang
        $assignedStudentIds = StudentAssignment::where('guru_id', Auth::id())
            ->where('academic_year', $currentAcademicYear)
            ->pluck('student_id');

        $students = Student::whereIn('id', $assignedStudentIds)->get();

        // Fallback jika belum ada record StudentAssignment spesifik
        if ($students->isEmpty()) {
            $students = Student::where('guru_id', Auth::id())->get();
        }

        $surahsList = Surah::orderBy('nomor');

        return view('guru.hafalan.create', compact('students', 'surahsList', 'currentAcademicYear'));
    }

    public function store(StoreHafalanRequest $request)
    {
        $data = $request->validated();
        $data['guru_id'] = Auth::id();

        if ($request->is_present) {
            $surahInfo = Surah::all()->firstWhere('nama_latin', $request->surah);
            $dari = (int) $request->ayat_dari;
            $sampai = (int) $request->ayat_sampai;
            $data['ayat'] = ($dari === $sampai) ? (string) $dari : "{$dari}-{$sampai}";

            if ($surahInfo) {
                $maxAyat = (int) $surahInfo->jumlah_ayat;
                if ($sampai > $maxAyat || $dari > $maxAyat) {
                    return back()->withInput()->withErrors(['ayat_sampai' => "Gagal: Surah {$surahInfo->nama_latin} hanya memiliki {$maxAyat} ayat."]);
                }
                if ($sampai < $dari) {
                    return back()->withInput()->withErrors(['ayat_sampai' => "Gagal: Ayat yang Anda Inputkan tidak Valid."]);
                }
            }

            $alreadyLancar = RiwayatHafalan::where('student_id', $request->student_id)
                ->where('surah', $request->surah)
                ->where('ayat', $data['ayat'])
                ->where('is_present', true)
                ->where('status', 'Lancar')
                ->exists();

            if ($alreadyLancar) {
                return back()->withInput()->withErrors(['surah' => "Santri sudah menyetorkan Surah {$request->surah} ayat {$data['ayat']} dengan status Lancar."]);
            }
        }

        if (!$request->is_present) {
            $data['juz'] = null;
            $data['surah'] = null;
            $data['ayat'] = null;
            $data['status'] = null;
        }

        $hafalan = RiwayatHafalan::create($data);
        $hafalan->student->refreshCache();

        broadcast(new HafalanUpdated("Siswa " . Auth::user()->name . " telah melakukan setoran baru!"))->toOthers();

        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil disimpan.');
    }

    public function exportPdf(Student $student)
    {
        $this->authorize('view', $student);
        $pdf = $this->memorizationService->generateStudentReport($student, 20);
        return $pdf->download('Raport_Tahfidz_' . $student->nis . '.pdf');
    }

    public function exportSemesterPdf(Request $request, Student $student)
    {
        $this->authorize('view', $student);
        $student->load(['memorizations', 'guru', 'targets']);

        $semester = $request->input('semester');
        $year = $request->input('year');

        $pdf = $this->memorizationService->generateSemesterRecap($student, $semester, $year);

        $filename = 'Rekap_Semester_' . ($semester ? ucfirst($semester) . '_' : '') . ($year ? str_replace('/', '-', $year) . '_' : '') . $student->nis . '.pdf';

        return $pdf->download($filename);
    }
}
