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

    public function create(Request $request)
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

        $students->load(['memorizations' => function ($q) {
            $q->where('is_present', true)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc');
        }]);

        $selectedStudentId = $request->input('student_id', old('student_id'));

        $surahsList = Surah::orderBy('nomor');

        return view('guru.hafalan.create', compact('students', 'surahsList', 'currentAcademicYear', 'selectedStudentId'));
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

            // Cek apakah ada ayat dalam rentang yang diminta yang sudah pernah disetorkan dengan status 'Lancar'
            $existingLancarRecords = RiwayatHafalan::where('student_id', $request->student_id)
                ->where('is_present', true)
                ->where('status', 'Lancar')
                ->get();

            $normalizedInputSurah = str_replace(["'", "-", " ", "‘", "’", "`", "´"], "", strtolower($request->surah ?? ''));

            $alreadyLancarVerses = [];
            foreach ($existingLancarRecords as $mem) {
                $normalizedMemSurah = str_replace(["'", "-", " ", "‘", "’", "`", "´"], "", strtolower($mem->surah ?? ''));
                if ($normalizedMemSurah !== $normalizedInputSurah) {
                    continue;
                }

                $range = trim($mem->ayat ?? '');
                if (str_contains($range, '-') || str_contains($range, '–')) {
                    $parts = preg_split('/[-–]/', $range);
                    $start = (int) ($parts[0] ?? 0);
                    $end = (int) ($parts[1] ?? 0);
                    if ($start > 0 && $end >= $start) {
                        for ($v = $start; $v <= $end; $v++) {
                            $alreadyLancarVerses[$v] = true;
                        }
                    }
                } else {
                    $v = (int) $range;
                    if ($v > 0) {
                        $alreadyLancarVerses[$v] = true;
                    }
                }
            }

            $requestedVerses = range($dari, $sampai);
            $overlappedVerses = [];
            foreach ($requestedVerses as $v) {
                if (isset($alreadyLancarVerses[$v])) {
                    $overlappedVerses[] = $v;
                }
            }

            if (!empty($overlappedVerses)) {
                $overlappedStr = $this->formatVerseRanges($overlappedVerses);
                return back()->withInput()->withErrors([
                    'surah' => "Santri sudah menyetorkan Surah {$request->surah} ayat {$overlappedStr} dengan status Lancar."
                ]);
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

        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil disimpan.');
    }

    private function formatVerseRanges(array $verses): string
    {
        sort($verses);
        $ranges = [];
        $count = count($verses);
        if ($count === 0) {
            return '';
        }

        $start = $verses[0];
        $end = $verses[0];

        for ($i = 1; $i < $count; $i++) {
            if ($verses[$i] === $end + 1) {
                $end = $verses[$i];
            } else {
                $ranges[] = ($start === $end) ? (string) $start : "{$start}–{$end}";
                $start = $verses[$i];
                $end = $verses[$i];
            }
        }
        $ranges[] = ($start === $end) ? (string) $start : "{$start}–{$end}";

        return implode(', ', $ranges);
    }

    public function exportPdf(Student $student)
    {
        $this->authorize('view', $student);
        $pdf = $this->memorizationService->generateStudentReport($student, 20);
        return $pdf->download('Raport_Tahfidz_' . $student->nis . '.pdf');
    }
}
