<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
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

    public function index()
    {
        $hafalan = RiwayatHafalan::with('student')
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

        if ($request->is_present) {
            // -- Server-side verse validation & formatting --
            $surahInfo = Surah::all()->firstWhere('nama_latin', $request->surah);
            $dari = (int) $request->ayat_dari;
            $sampai = (int) $request->ayat_sampai;
            
            // Format for database: single number or range
            $data['ayat'] = ($dari === $sampai) ? (string) $dari : "{$dari}-{$sampai}";

            if ($surahInfo) {
                $maxAyat = (int) $surahInfo->jumlah_ayat;
                if ($sampai > $maxAyat || $dari > $maxAyat) {
                    return back()->withInput()->withErrors([
                        'ayat_sampai' => "Gagal: Surah {$surahInfo->nama_latin} hanya memiliki {$maxAyat} ayat."
                    ]);
                }
                if ($sampai < $dari) {
                    return back()->withInput()->withErrors([
                        'ayat_sampai' => "Gagal: Ayat sampai tidak boleh lebih kecil dari ayat dari."
                    ]);
                }
            }
        }

        if (! $request->is_present) {
            $data['juz'] = null;
            $data['surah'] = null;
            $data['ayat'] = null;
            $data['status'] = null;
        }

        RiwayatHafalan::create($data);

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
            // -- Server-side verse validation & formatting --
            $surahInfo = Surah::all()->firstWhere('nama_latin', $request->surah);
            $dari = (int) $request->ayat_dari;
            $sampai = (int) $request->ayat_sampai;
            
            // Format for database
            $data['ayat'] = ($dari === $sampai) ? (string) $dari : "{$dari}-{$sampai}";

            if ($surahInfo) {
                $maxAyat = (int) $surahInfo->jumlah_ayat;
                if ($sampai > $maxAyat || $dari > $maxAyat) {
                    return back()->withInput()->withErrors([
                        'ayat_sampai' => "Gagal: Surah {$surahInfo->nama_latin} hanya memiliki {$maxAyat} ayat."
                    ]);
                }
                if ($sampai < $dari) {
                    return back()->withInput()->withErrors([
                        'ayat_sampai' => "Gagal: Ayat sampai tidak boleh lebih kecil dari ayat dari."
                    ]);
                }
            }
        }

        if (! $request->is_present) {
            $data['juz'] = null;
            $data['surah'] = null;
            $data['ayat'] = null;
            $data['status'] = null;
        }

        $hafalan->update($data);

        broadcast(new HafalanUpdated("Update hafalan oleh " . Auth::user()->name))->toOthers();

        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(RiwayatHafalan $hafalan)
    {
        $this->authorize('delete', $hafalan);

        $hafalan->delete();

        return redirect()->route('guru.hafalan.index')->with('success', 'Data berhasil dihapus.');
    }

    public function exportPdf(Student $student)
    {
        $this->authorize('view', $student);

        $pdf = $this->memorizationService->generateStudentReport($student, 20);

        return $pdf->download('Raport_Tahfidz_'.$student->nis.'.pdf');
    }

    public function exportSemesterPdf(Student $student)
    {
        $this->authorize('view', $student);

        $pdf = $this->memorizationService->generateSemesterRecap($student);

        return $pdf->download('Rekap_Semester_'.$student->nis.'.pdf');
    }
}
