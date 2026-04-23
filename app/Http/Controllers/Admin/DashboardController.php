<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Memorization;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $month = (int) request('month', now()->month);
        $year = (int) request('year', now()->year);

        $guruCount = User::where('role', 'guru')->count();
        $studentCount = Student::count();
        $hafalanCount = Memorization::count();
        $lancarPercent = $hafalanCount > 0
            ? round((Memorization::where('status', 'Lancar')->count() / $hafalanCount) * 100)
            : 0;

        // Date range for the selected month and year
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Monthly chart data: Dividing month into 4 weeks
        $weeklyLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $weeklyData = [];
        
        $ranges = [
            [1, 7],
            [8, 14],
            [15, 21],
            [22, $endDate->day]
        ];

        foreach ($ranges as $range) {
            $weekStart = Carbon::createFromDate($year, $month, $range[0])->startOfDay();
            $weekEnd = Carbon::createFromDate($year, $month, $range[1])->endOfDay();
            $weeklyData[] = Memorization::whereBetween('created_at', [$weekStart, $weekEnd])->count();
        }

        // Teacher performance filtered by selected month and year
        $teacher_performance = User::where('role', 'guru')
            ->withCount('studentsAsGuru')
            ->get()
            ->map(function ($guru) use ($year, $month) {
                $guru->total_memorizations = Memorization::where('guru_id', $guru->id)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();

                return $guru;
            })
            ->sortByDesc('total_memorizations');

        return view('admin.dashboard', compact(
            'guruCount',
            'studentCount',
            'hafalanCount',
            'lancarPercent',
            'weeklyLabels',
            'weeklyData',
            'teacher_performance',
            'month',
            'year'
        ));
    }
}
