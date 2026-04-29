<?php

namespace App\Services;

use App\Models\Student;
use App\Models\RiwayatHafalan;
use App\Helpers\PdfHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RiwayatHafalanService
{
    /**
     * Generate student report PDF.
     *
     * @param Student $student
     * @param int|null $limit
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateStudentReport(Student $student, $limit = null)
    {
        $query = $student->memorizations()->with('guru')->latest();
        
        if ($limit) {
            $query->take($limit);
        }

        $memorizations = $query->get();
        $logoBase64 = PdfHelper::getLogoBase64();

        return Pdf::loadView('pdf.student_report', [
            'student' => $student,
            'memorizations' => $memorizations,
            'logoBase64' => $logoBase64
        ]);
    }

    /**
     * Generate semester recap PDF.
     *
     * @param Student $student
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateSemesterRecap(Student $student)
    {
        $memorizations = $student->memorizations()
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at', 'asc')
            ->get();

        $logoBase64 = PdfHelper::getLogoBase64();

        return Pdf::loadView('pdf.semester_recap', [
            'student' => $student,
            'memorizations' => $memorizations,
            'logoBase64' => $logoBase64
        ]);
    }

    /**
     * Calculate student progress analytics.
     *
     * @param Student $student
     * @return array
     */
    public function getAnalytics(Student $student)
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

        // Attendance Heatmap (90 Days)
        $attendance = [];
        for ($i = 89; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $status = $analyticsData->first(fn ($m) => $m->created_at->format('Y-m-d') === $date);
            $attendance[$date] = $status ? ($status->is_present ? 'present' : 'absent') : 'none';
        }

        // Quality Chart (30 Days)
        $last30Days = $analyticsData->filter(fn ($m) => $m->created_at >= now()->subDays(30) && $m->is_present);
        
        return [
            'trends' => $trends,
            'attendance' => $attendance,
            'quality' => [
                'lancar' => $last30Days->where('status', 'Lancar')->count(),
                'perbaikan' => $last30Days->where('status', 'Perlu Perbaikan')->count(),
            ],
            'latest_notes' => $student->memorizations()
                ->whereNotNull('notes')
                ->where('notes', '!=', '')
                ->latest()
                ->first()?->notes
        ];
    }

    /**
     * Get prediction for student memorization completion.
     *
     * @param Student $student
     * @return string
     */
    public function getPrediction(Student $student)
    {
        $setorans = $student->memorizations()
            ->where('is_present', true)
            ->whereNotNull('juz')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($setorans->count() < 3) {
            return 'Dalam Evaluasi';
        }

        $first = $setorans->first();
        $last = $setorans->last();

        // Calculate unique juz count at different points
        $totalUniqueJuz = $student->memorizations()
            ->where('is_present', true)
            ->whereNotNull('juz')
            ->distinct()
            ->count('juz');

        $startingUniqueJuz = $student->memorizations()
            ->where('is_present', true)
            ->whereNotNull('juz')
            ->where('created_at', '<=', $first->created_at->addDay())
            ->distinct()
            ->count('juz');

        $juzGained = $totalUniqueJuz - $startingUniqueJuz;
        $days = $last->created_at->diffInDays($first->created_at);

        if ($juzGained > 0 && $days > 0) {
            $daysPerJuz = $days / $juzGained;
            $juzRemaining = $student->target_juz - $totalUniqueJuz;
            
            if ($juzRemaining <= 0) {
                return 'Selesai ✨';
            }

            $daysLeft = $juzRemaining * $daysPerJuz;
            return now()->addDays($daysLeft)->format('M Y');
        } 

        if ($juzGained === 0 && $days > 30) {
            return 'Butuh Semangat 🔥';
        }

        return 'Dalam Evaluasi';
    }
}
