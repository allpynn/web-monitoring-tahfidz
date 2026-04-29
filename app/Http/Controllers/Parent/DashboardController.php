<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\RiwayatHafalan;
use App\Services\RiwayatHafalanService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    protected $memorizationService;

    public function __construct(RiwayatHafalanService $memorizationService)
    {
        $this->memorizationService = $memorizationService;
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $students = $user->students()
            ->with(['memorizations' => fn ($q) => $q->latest()])
            ->get();

        foreach ($students as $student) {
            $analytics = $this->memorizationService->getAnalytics($student);
            
            $student->prediction = $this->memorizationService->getPrediction($student);
            $student->trend_data = $analytics['trends'];
            $student->attendance_heatmap = $analytics['attendance'];
            $student->quality_chart_data = $analytics['quality'];
            $student->latest_notes = $analytics['latest_notes'];
        }

        return view('parent.dashboard', compact('students'));
    }

    public function updateComment(UpdateCommentRequest $request, RiwayatHafalan $memorization)
    {
        $this->authorize('update', $memorization);

        $memorization->update($request->validated());

        return back()->with('success', 'Feedback berhasil dikirim.');
    }
}
