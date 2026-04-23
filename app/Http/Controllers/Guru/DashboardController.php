<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Memorization;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $month = (int) request('month', now()->month);
        $year = (int) request('year', now()->year);

        $stats = [
            'total_hafalan' => Memorization::where('guru_id', Auth::id())->where('is_present', true)->count(),
            'total_absensi' => Memorization::where('guru_id', Auth::id())->count(),
            'today_entries' => Memorization::where('guru_id', Auth::id())->whereDate('created_at', today())->count(),
        ];

        // Weekly chart data for this Guru: Dividing month into 4 weeks
        $weeklyLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $weeklyData = [];
        
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        $ranges = [
            [1, 7],
            [8, 14],
            [15, 21],
            [22, $endDate->day]
        ];

        foreach ($ranges as $range) {
            $weekStart = Carbon::createFromDate($year, $month, $range[0])->startOfDay();
            $weekEnd = Carbon::createFromDate($year, $month, $range[1])->endOfDay();
            $weeklyData[] = Memorization::where('guru_id', Auth::id())
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->count();
        }

        $recent_activities = Memorization::with('student')
            ->where('guru_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $parent_feedbacks = Memorization::with('student')
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

        return view('guru.dashboard', compact('stats', 'weeklyLabels', 'weeklyData', 'recent_activities', 'parent_feedbacks', 'early_warnings', 'top_targets', 'month', 'year'));
    }
}
