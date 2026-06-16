<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreHafalanRequest;
use App\Http\Requests\UpdateHafalanRequest;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\Surah;

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
        $perPage = $request->input('per_page', 25);
        $search = $request->input('search');
        $date = $request->input('date');
        $status = $request->input('status');
        $presence = $request->input('presence');

        $query = RiwayatHafalan::with('student')
            ->where('guru_id', Auth::id());

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
                    return back()->withInput()->withErrors(['ayat_sampai' => "Gagal: Ayat sampai tidak boleh lebih kecil dari ayat dari."]);
                }
            }

            $exists = RiwayatHafalan::where('student_id', $request->student_id)
                ->where('surah', $request->surah)
                ->where('ayat', $data['ayat'])
                ->where('is_present', true)
                ->whereIn('status', ['Lancar', 'Perlu Perbaikan'])
                ->exists();

            if ($exists) {
                return back()->withInput()->withErrors(['surah' => "Santri sudah pernah menyetorkan Surah {$request->surah} ayat {$data['ayat']} dengan status Lancar/Perlu Perbaikan."]);
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

    public function edit(RiwayatHafalan $hafalan)
    {
        $this->authorize('update', $hafalan);
        $students = Student::where('guru_id', Auth::id())->get();
        $surahsList = Surah::orderBy('nomor');
        return view('guru.hafalan.edit', compact('hafalan', 'students', 'surahsList'));
    }

    public function update(UpdateHafalanRequest $request, RiwayatHafalan $hafalan)
    {
        $data = $request->validated();

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
                    return back()->withInput()->withErrors(['ayat_sampai' => "Gagal: Ayat sampai tidak boleh lebih kecil dari ayat dari."]);
                }
            }

            // Duplicate Check (excluding current record)
            $exists = RiwayatHafalan::where('student_id', $request->student_id)
                ->where('surah', $request->surah)
                ->where('ayat', $data['ayat'])
                ->where('is_present', true)
                ->whereIn('status', ['Lancar', 'Perlu Perbaikan'])
                ->where('id', '!=', $hafalan->id)
                ->exists();

            if ($exists) {
                return back()->withInput()->withErrors(['surah' => "Santri sudah pernah menyetorkan Surah {$request->surah} ayat {$data['ayat']} dengan status Lancar/Perlu Perbaikan."]);
            }
        }

        if (!$request->is_present) {
            $data['juz'] = null;
            $data['surah'] = null;
            $data['ayat'] = null;
            $data['status'] = null;
        }

        $hafalan->update($data);
        $hafalan->student->refreshCache();

        broadcast(new HafalanUpdated("Update hafalan oleh " . Auth::user()->name))->toOthers();

        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(RiwayatHafalan $hafalan)
    {
        $this->authorize('delete', $hafalan);
        $student = $hafalan->student;
        $hafalan->delete();
        $student->refreshCache();

        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil dihapus.');
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
