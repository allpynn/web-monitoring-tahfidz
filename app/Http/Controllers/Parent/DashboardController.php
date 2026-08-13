<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\Pesan;
use App\Events\MessageSent;
use Illuminate\Http\Request;
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
        $user = Auth::user();

        $students = $user->students()
            ->with(['memorizations' => fn ($q) => $q->latest()])
            ->paginate(2);

        foreach ($students as $student) {
            $analytics = $this->memorizationService->getAnalytics($student);
            
            $student->prediction = $this->memorizationService->getPrediction($student);
            $student->trend_data = $analytics['trends'];
            $student->attendance_heatmap = $analytics['attendance'];
            $student->quality_chart_data = $analytics['quality'];
            $student->latest_notes = $analytics['latest_notes'];
            
            $student->messages = Pesan::with(['sender', 'receiver'])
                ->where('student_id', $student->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('parent.dashboard', compact('students'));
    }

    public function sendMessage(Request $request, Student $student)
    {
        $request->validate(['message' => 'required|string']);

        $p = Pesan::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $student->guru_id,
            'student_id' => $student->id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($p))->toOthers();

        if ($request->ajax()) {
            $p->load(['sender', 'student']);
            return response()->json([
                'success' => true,
                'message' => $p,
            ]);
        }

        return back()->with('success', 'Pesan berhasil dikirim ke Ustadz.');
    }
}
