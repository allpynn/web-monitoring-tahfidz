<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Memorization;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $students = auth()->user()->students()
            ->with(['memorizations' => fn ($q) => $q->latest()])
            ->get();

        foreach ($students as $student) {
            $this->calculateProgress($student);
            $this->generateAnalytics($student);
        }

        return view('parent.dashboard', compact('students'));
    }

    private function calculateProgress($student)
    {
        $student->prediction = 'Dalam Evaluasi';
        $setorans = $student->memorizations->where('is_present', true);

        if ($setorans->count() > 3) {
            $first = $setorans->last();
            $last = $setorans->first();

            if ($first->id !== $last->id) {
                $juzGained = $last->juz - $first->juz;
                $days = $last->created_at->diffInDays($first->created_at);

                if ($juzGained > 0 && $days > 0) {
                    $daysLeft = ($student->target_juz - $student->current_juz) * ($days / $juzGained);
                    $student->prediction = $daysLeft > 0 ? now()->addDays($daysLeft)->format('M Y') : 'Selesai ✨';
                } elseif ($juzGained === 0 && $days > 30) {
                    $student->prediction = 'Butuh Semangat 🔥';
                }
            }
        }
    }

    private function generateAnalytics($student)
    {
        $analyticsData = $student->memorizations()
            ->where('created_at', '>=', now()->subDays(90))
            ->get();

        // Trend Chart (8 Weeks)
        $trends = [];
        for ($i = 7; $i >= 0; $i--) {
            $start = now()->subWeeks($i)->startOfWeek();
            $end = now()->subWeeks($i)->endOfWeek();

            $trends[] = [
                'label' => 'Mgu '.$start->format('d/m'),
                'value' => $analyticsData->filter(fn ($m) => $m->created_at >= $start && $m->created_at <= $end && $m->status === 'Lancar')->count(),
            ];
        }
        $student->trend_data = $trends;

        // Attendance Heatmap (90 Days)
        $attendance = [];
        for ($i = 89; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $status = $analyticsData->first(fn ($m) => $m->created_at->format('Y-m-d') === $date);
            $attendance[$date] = $status ? ($status->is_present ? 'present' : 'absent') : 'none';
        }
        $student->attendance_heatmap = $attendance;

        // Quality Chart (30 Days)
        $last30Days = $analyticsData->filter(fn ($m) => $m->created_at >= now()->subDays(30) && $m->is_present);
        $student->quality_chart_data = [
            'lancar' => $last30Days->where('status', 'Lancar')->count(),
            'perbaikan' => $last30Days->where('status', 'Perlu Perbaikan')->count(),
        ];

        // Latest notes
        $student->latest_notes = $student->memorizations()
            ->whereNotNull('notes')
            ->where('notes', '!=', '')
            ->latest()
            ->first()?->notes;
    }

    public function updateComment(UpdateCommentRequest $request, Memorization $memorization)
    {
        $memorization->update($request->validated());

        return back()->with('success', 'Feedback berhasil dikirim.');
    }
}
