<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_hafalan' => \App\Models\Memorization::where('guru_id', auth()->id())->where('is_present', true)->count(),
            'total_absensi' => \App\Models\Memorization::where('guru_id', auth()->id())->count(),
            'today_entries' => \App\Models\Memorization::where('guru_id', auth()->id())->whereDate('created_at', today())->count(),
        ];
        
        // Weekly chart data for this Guru: last 7 days
        $weeklyLabels = [];
        $weeklyData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $weeklyLabels[] = $date->translatedFormat('D, d M');
            $weeklyData[]   = \App\Models\Memorization::where('guru_id', auth()->id())
                ->whereDate('created_at', $date->toDateString())
                ->count();
        }

        $recent_activities = \App\Models\Memorization::with('student')
            ->where('guru_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();
            
        $parent_feedbacks = \App\Models\Memorization::with('student')
            ->where('guru_id', auth()->id())
            ->whereNotNull('parent_comment')
            ->latest()
            ->take(5)
            ->get();

        $students = \App\Models\Student::where('guru_id', auth()->id())->with('memorizations')->get();
        
        $early_warnings = collect();
        $top_targets = collect();

        foreach ($students as $student) {
            $latestMem = $student->memorizations->last();
            
            // Early warning check
            if (!$latestMem || $latestMem->created_at->diffInDays(now()) > 3 || $latestMem->status === 'Perlu Perbaikan') {
                $student->warning_reason = !$latestMem ? 'Belum Ada Setoran' : ($latestMem->status === 'Perlu Perbaikan' ? 'Perlu Perbaikan' : 'Lama Tidak Setor');
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

        return view('guru.dashboard', compact('stats', 'weeklyLabels', 'weeklyData', 'recent_activities', 'parent_feedbacks', 'early_warnings', 'top_targets'));
    }
}
