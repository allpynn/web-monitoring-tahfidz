<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_hafalan' => RiwayatHafalan::where('guru_id', Auth::id())->where('is_present', true)->count(),
            'total_absensi' => RiwayatHafalan::where('guru_id', Auth::id())->count(),
            'today_entries' => RiwayatHafalan::where('guru_id', Auth::id())->whereDate('created_at', today())->count(),
        ];

        $recent_activities = RiwayatHafalan::with('student')
            ->where('guru_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $parent_feedbacks = RiwayatHafalan::with('student')
            ->where('guru_id', Auth::id())
            ->whereNotNull('parent_comment')
            ->latest()
            ->take(5)
            ->get();

        $students = Student::where('guru_id', Auth::id())->with('memorizations')->get();

        $early_warnings = collect();
        $top_targets = collect();

        foreach ($students as $student) {
            $latestMem = $student->memorizations->last();

            // Early warning check
            if (! $latestMem || $latestMem->created_at->diffInDays(now()) > 3 || $latestMem->status === 'Perlu Perbaikan') {
                $student->warning_reason = ! $latestMem ? 'Belum Ada Setoran' : ($latestMem->status === 'Perlu Perbaikan' ? 'Perlu Perbaikan' : 'Lama Tidak Setor');
                $student->last_mem_date = $latestMem ? $latestMem->created_at : null;
                $early_warnings->push($student);
            }

            // Target progress calculation
            $progress = $student->target_juz > 0 ? round(($student->current_juz / $student->target_juz) * 100) : 0;
            $student->progress_percent = min($progress, 100);
            $top_targets->push($student);
        }

        // Sort early warnings by reason severity (Perlu perbaikan > lama tidak setor) - simplistic just take 5
        $early_warnings = $early_warnings->take(5);

        // Sort top targets by progress descending
        $top_targets = $top_targets->sortByDesc('progress_percent')->take(3);

        return view('guru.dashboard', compact('stats', 'recent_activities', 'parent_feedbacks', 'early_warnings', 'top_targets'));
    }
}
