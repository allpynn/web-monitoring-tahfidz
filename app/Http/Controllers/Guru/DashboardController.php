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
    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear  = now()->year;
        
        $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $defaultAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);
        $defaultSemester = ($currentMonth >= 7) ? 'Ganjil' : 'Genap';

        $academicYear = $request->input('academic_year', $defaultAcademicYear);
        $semester     = $request->input('semester', $defaultSemester);

        // Parse academic year (split 2025/2026)
        $years = explode('/', $academicYear);
        $baseYear = (int) $years[0];

        if ($semester === 'Ganjil') {
            $startDate = Carbon::create($baseYear, 7, 1)->startOfDay();
            $endDate   = Carbon::create($baseYear, 12, 31)->endOfDay();
            $months    = [7, 8, 9, 10, 11, 12];
        } else {
            $startDate = Carbon::create($baseYear + 1, 1, 1)->startOfDay();
            $endDate   = Carbon::create($baseYear + 1, 6, 30)->endOfDay();
            $months    = [1, 2, 3, 4, 5, 6];
        }

        $isCurrentPeriod = now()->between($startDate, $endDate);
        $guruId = Auth::id();

        $stats = [
            'total_overall_santri' => Student::where('guru_id', $guruId)
                ->orWhereHas('memorizations', fn($q) => $q->where('guru_id', $guruId))
                ->count('id'),
            'total_santri_diampu'  => $isCurrentPeriod
                ? Student::where('guru_id', $guruId)->count()
                : RiwayatHafalan::where('guru_id', $guruId)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->distinct('student_id')
                    ->count('student_id'),
            'today_entries'        => RiwayatHafalan::where('guru_id', $guruId)->whereDate('tanggal', today())->count(),
        ];

        $recent_activities = RiwayatHafalan::with('student')
            ->where('guru_id', $guruId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $parent_messages = Pesan::with(['sender', 'student'])
            ->where('receiver_id', $guruId)
            ->where('is_resolved', false)
            ->latest()
            ->get()
            ->unique('student_id')
            ->take(5);

        foreach ($parent_messages as $thread) {
            $thread->conversation = Pesan::where('student_id', $thread->student_id)
                ->where(function($q) use ($guruId, $thread) {
                    $q->where('sender_id', $guruId)->where('receiver_id', $thread->sender_id)
                      ->orWhere('sender_id', $thread->sender_id)->where('receiver_id', $guruId);
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        $students = Student::where('guru_id', $guruId)
            ->with([
                'targets',
                'memorizations' => fn($q) => $q->select('id','student_id','surah','ayat','juz','status','is_present','tanggal')
            ])
            ->get();

        $early_warnings = collect();
        $top_targets    = collect();

        foreach ($students as $student) {
            $latestMem = $student->memorizations->sortByDesc('tanggal')->first();
            if (
                !$latestMem || 
                Carbon::parse($latestMem->tanggal)->diffInDays(now()) >= 3 || 
                $latestMem->status === 'Perlu Perbaikan'
            ) {
                $student->warning_reason = !$latestMem ? 'Belum Ada Setoran' : ($latestMem->status === 'Perlu Perbaikan' ? 'Perlu Perbaikan' : 'Lama Tidak Setor (≥ 3 Hari)');
                $student->last_mem_date = $latestMem ? Carbon::parse($latestMem->tanggal) : null;
                $early_warnings->push($student);
            }

            $activeTarget = $student->activeTarget();
            $student->progress_percent = $student->target_progress;
            $top_targets->push($student);
        }

        $early_warnings = $early_warnings->take(5);
        $top_targets    = $top_targets->sortByDesc('progress_percent')->take(5);

        // Achievement Chart: Overall student progress distribution (Not filtered)
        $achievementStats = Student::where('guru_id', $guruId)
            ->get()
            ->groupBy(function($s) {
                return count($s->completed_juz);
            })
            ->map->count();

        $weeklyLabels = [];
        $weeklyData   = [];

        // Determine max juz achieved to set range
        $maxAchieved = $achievementStats->keys()->max() ?: 5;
        
        for ($i = 0; $i <= $maxAchieved; $i++) {
            $weeklyLabels[] = $i . " Juz";
            $weeklyData[]   = $achievementStats->get($i, 0);
        }

        return view('guru.dashboard', compact(
            'stats', 'recent_activities', 'parent_messages',
            'early_warnings', 'top_targets',
            'weeklyLabels', 'weeklyData', 'academicYear', 'semester'
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

    public function destroyMessage(Pesan $pesan)
    {
        if ($pesan->receiver_id !== Auth::id()) {
            abort(403);
        }

        Pesan::where('student_id', $pesan->student_id)
            ->where(function($q) use ($pesan) {
                $q->where('sender_id', $pesan->sender_id)->where('receiver_id', Auth::id())
                  ->orWhere('sender_id', Auth::id())->where('receiver_id', $pesan->sender_id);
            })
            ->update(['is_resolved' => true]);

        return back()->with('success', 'Percakapan telah diselesaikan dan dipindahkan dari antrean.');
    }
}
