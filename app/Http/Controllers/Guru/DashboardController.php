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
        $year = (int) request('year', now()->year);

        $stats = [
            'total_hafalan' => RiwayatHafalan::where('guru_id', Auth::id())->where('is_present', true)->count(),
            'total_absensi' => RiwayatHafalan::where('guru_id', Auth::id())->count(),
            'today_entries' => RiwayatHafalan::where('guru_id', Auth::id())->whereDate('tanggal', today())->count(),
        ];

        $recent_activities = RiwayatHafalan::with('student')
            ->where('guru_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $parent_messages = Pesan::with(['sender', 'student'])
            ->where('receiver_id', Auth::id())
            ->latest()
            ->get()
            ->unique('student_id')
            ->take(5);

        $students = Student::where('guru_id', Auth::id())->with('memorizations')->get();

        $early_warnings = collect();
        $top_targets = collect();

        foreach ($students as $student) {
            $latestMem = $student->memorizations->sortByDesc('tanggal')->first();

            // Early warning check based on 'tanggal'
            if (! $latestMem || \Carbon\Carbon::parse($latestMem->tanggal)->diffInDays(now()) >= 3 || $latestMem->status === 'Perlu Perbaikan') {
                $student->warning_reason = ! $latestMem ? 'Belum Ada Setoran' : ($latestMem->status === 'Perlu Perbaikan' ? 'Perlu Perbaikan' : 'Lama Tidak Setor (≥ 3 Hari)');
                $student->last_mem_date = $latestMem ? \Carbon\Carbon::parse($latestMem->tanggal) : null;
                $early_warnings->push($student);
            }

            // Target progress calculation
            $progress = $student->target_juz > 0 ? round(($student->current_juz / $student->target_juz) * 100) : 0;
            $student->progress_percent = min($progress, 100);
            $top_targets->push($student);
        }

        $early_warnings = $early_warnings->take(5);
        $top_targets = $top_targets->sortByDesc('progress_percent')->take(3);

        // Monthly chart data: Dividing month into 4 weeks
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        $weeklyLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $weeklyData = [];
        
        $ranges = [[1, 7], [8, 14], [15, 21], [22, $endDate->day]];

        foreach ($ranges as $range) {
            $weekStart = Carbon::createFromDate($year, $month, $range[0])->startOfDay();
            $weekEnd = Carbon::createFromDate($year, $month, $range[1])->endOfDay();
            $weeklyData[] = RiwayatHafalan::where('guru_id', Auth::id())
                ->whereBetween('tanggal', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
                ->count();
        }

        return view('guru.dashboard', compact(
            'stats', 'recent_activities', 'parent_messages', 'early_warnings', 'top_targets',
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
