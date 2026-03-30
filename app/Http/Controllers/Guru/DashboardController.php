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
        
        return view('guru.dashboard', compact('stats', 'weeklyLabels', 'weeklyData'));
    }
}
