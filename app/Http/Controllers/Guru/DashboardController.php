<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\Pesan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $month = (int) request('month', now()->month);
        $year  = (int) request('year',  now()->year);

        $guruId = Auth::id();

        $stats = [
            'total_hafalan'  => RiwayatHafalan::where('guru_id', $guruId)->where('is_present', true)->count(),
            'total_santri'   => Student::where('guru_id', $guruId)->count(),
            'today_entries'  => RiwayatHafalan::where('guru_id', $guruId)->whereDate('tanggal', today())->count(),
        ];

        $recent_activities = RiwayatHafalan::with('student')
            ->where('guru_id', $guruId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $parent_messages = Pesan::with(['sender', 'student'])
            ->where('receiver_id', $guruId)
            ->latest()
            ->get()
            ->unique('student_id')
            ->take(5);

        // Load students with memorizations & targets in ONE query
        $students = Student::where('guru_id', $guruId)
            ->with([
                'targets',
                'memorizations' => fn($q) => $q->select('id','student_id','surah','ayat','juz','status','is_present','tanggal')
            ])
            ->get();

        // Pre-load surah data ONCE (not in loop)
        $allSurahs = \App\Models\Surah::all();
        $surahsMap = $allSurahs->keyBy('nama_latin');

        // Pre-calculate total ayat per juz level efficiently
        $ayatPerJuzLevel = [];
        for ($j = 1; $j <= 30; $j++) {
            $ayatPerJuzLevel[$j] = $allSurahs
                ->filter(fn($s) => $s->juz_awal <= $j)
                ->sum('jumlah_ayat');
        }

        $early_warnings = collect();
        $top_targets    = collect();

        foreach ($students as $student) {
            $mems = $student->memorizations;

            // ── Early Warning ──────────────────────────────────────────
            $latestMem = $mems->sortByDesc('tanggal')->first();
            if (
                ! $latestMem
                || \Carbon\Carbon::parse($latestMem->tanggal)->diffInDays(now()) >= 3
                || $latestMem->status === 'Perlu Perbaikan'
            ) {
                $student->warning_reason  = ! $latestMem
                    ? 'Belum Ada Setoran'
                    : ($latestMem->status === 'Perlu Perbaikan' ? 'Perlu Perbaikan' : 'Lama Tidak Setor (≥ 3 Hari)');
                $student->last_mem_date = $latestMem
                    ? \Carbon\Carbon::parse($latestMem->tanggal)
                    : null;
                $early_warnings->push($student);
            }

            // ── Smart Progress (ayat-based) ────────────────────────────
            $lancarRecords = $mems->where('status', 'Lancar')->where('is_present', true);

            $ayatLancarCount = 0;
            $seen = [];
            foreach ($lancarRecords as $record) {
                if (!$record->surah) continue;
                $key = $record->surah . '|' . ($record->ayat ?? '');
                if (isset($seen[$key])) continue;
                $seen[$key] = true;

                $ayat = trim($record->ayat ?? '');
                if (preg_match('/^(\d+)-(\d+)$/', $ayat, $m)) {
                    $ayatLancarCount += abs((int)$m[2] - (int)$m[1]) + 1;
                } else {
                    $ayatLancarCount += 1;
                }
            }

            $targetJuz        = (int) ($student->target_juz ?? 30);
            $totalAyatTarget  = $ayatPerJuzLevel[$targetJuz] ?? 0;

            $student->progress_percent  = $totalAyatTarget > 0
                ? min((int) round($ayatLancarCount / $totalAyatTarget * 100), 100)
                : 0;
            $student->ayat_lancar       = $ayatLancarCount;
            $student->total_ayat_target = $totalAyatTarget;

            $top_targets->push($student);
        }

        $early_warnings = $early_warnings->take(5);
        $top_targets    = $top_targets->sortByDesc('progress_percent')->take(5);

        // Monthly chart — 4 weeks (plain array, no DB loop)
        $endDay       = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $weeklyLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $ranges       = [[1, 7], [8, 14], [15, 21], [22, $endDay]];
        $weeklyData   = [];

        foreach ($ranges as [$from, $to]) {
            $weeklyData[] = RiwayatHafalan::where('guru_id', $guruId)
                ->whereBetween('tanggal', [
                    Carbon::createFromDate($year, $month, $from)->format('Y-m-d'),
                    Carbon::createFromDate($year, $month, $to)->format('Y-m-d'),
                ])
                ->count();
        }

        return view('guru.dashboard', compact(
            'stats', 'recent_activities', 'parent_messages',
            'early_warnings', 'top_targets',
            'weeklyLabels', 'weeklyData', 'month', 'year'
        ));
    }

    public function replyMessage(Request $request, Pesan $pesan)
    {
        $request->validate(['message' => 'required|string']);

        Pesan::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $pesan->sender_id,
            'student_id' => $pesan->student_id,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Balasan pesan berhasil dikirim.');
    }
}
