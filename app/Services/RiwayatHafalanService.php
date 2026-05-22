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
    public function getPrediction(Student $student): string
    {
        $target = $student->activeTarget();

        // Cek apakah target sudah tuntas
        $completedJuz = count($student->completed_juz);
        $targetJuz    = $target ? $target->target_juz : 0;

        if ($targetJuz > 0 && $completedJuz >= $targetJuz) {
            return 'Target Tuntas ✨';
        }

        // Jika ada target_date, gunakan sebagai patokan utama
        if ($target && $target->target_date) {
            $deadline = \Carbon\Carbon::parse($target->target_date);
            $now      = \Carbon\Carbon::now();

            if ($now->gt($deadline)) {
                // Sudah melewati deadline
                $overdueDays = $now->diffInDays($deadline);
                return 'Terlambat ' . $overdueDays . ' Hari ⚠️';
            }

            $daysLeft   = $now->diffInDays($deadline);
            $monthsLeft = $now->diffInMonths($deadline);

            if ($daysLeft <= 14) {
                return $daysLeft . ' Hari Lagi';
            } elseif ($monthsLeft < 1) {
                return $daysLeft . ' Hari Lagi';
            } elseif ($monthsLeft < 12) {
                return 'Sekitar ' . $monthsLeft . ' Bulan Lagi';
            } else {
                $yearsLeft = round($monthsLeft / 12, 1);
                return 'Sekitar ' . $yearsLeft . ' Tahun Lagi';
            }
        }

        // Fallback: hitung estimasi berdasarkan kecepatan hafalan
        $setorans = $student->memorizations()
            ->where('is_present', true)
            ->whereNotNull('juz')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($setorans->count() < 3) {
            return 'Menghitung...';
        }

        $first = $setorans->first();
        $last  = $setorans->last();
        $days  = $last->created_at->diffInDays($first->created_at);

        $totalUniqueJuz = $student->memorizations()
            ->where('is_present', true)->whereNotNull('juz')
            ->distinct()->count('juz');

        $startingUniqueJuz = $student->memorizations()
            ->where('is_present', true)->whereNotNull('juz')
            ->where('created_at', '<=', $first->created_at->addDay())
            ->distinct()->count('juz');

        $juzGained = $totalUniqueJuz - $startingUniqueJuz;

        if ($juzGained > 0 && $days > 0) {
            $daysPerJuz   = $days / $juzGained;
            $juzRemaining = $targetJuz - $totalUniqueJuz;

            if ($juzRemaining <= 0) {
                return 'Target Tuntas ✨';
            }

            $daysLeft = round($juzRemaining * $daysPerJuz);

            if ($daysLeft > 365) {
                return 'Sekitar ' . round($daysLeft / 365, 1) . ' Tahun';
            } elseif ($daysLeft > 30) {
                return 'Sekitar ' . round($daysLeft / 30) . ' Bulan';
            } else {
                return $daysLeft . ' Hari Lagi';
            }
        }

        if ($juzGained === 0 && $days > 14) {
            return 'Butuh Konsistensi 🔥';
        }

        return 'Menghitung...';
    }
}
