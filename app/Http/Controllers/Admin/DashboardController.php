<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Memorization;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $guruCount = User::where('role', 'guru')->count();
        $studentCount = Student::count();
        $hafalanCount = Memorization::count();
        $lancarPercent = $hafalanCount > 0
            ? round((Memorization::where('status', 'Lancar')->count() / $hafalanCount) * 100)
            : 0;

        // Weekly chart data: last 7 days
        $weeklyLabels = [];
        $weeklyData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyLabels[] = $date->translatedFormat('D, d M');
            $weeklyData[]   = Memorization::whereDate('created_at', $date->toDateString())->count();
        }

        return view('admin.dashboard', compact(
            'guruCount',
            'studentCount',
            'hafalanCount',
            'lancarPercent',
            'weeklyLabels',
            'weeklyData'
        ));
    }
}
