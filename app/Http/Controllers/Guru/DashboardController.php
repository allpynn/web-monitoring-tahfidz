<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\StudentAssignment;
use App\Models\Pesan;
use App\Events\MessageSent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $defaultAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);
        $defaultSemester = ($currentMonth >= 7) ? 'Ganjil' : 'Genap';

        $academicYear = $request->input('academic_year', $defaultAcademicYear);
        $semester = $request->input('semester', $defaultSemester);

        // Parse academic year (split 2025/2026)
        $years = explode('/', $academicYear);
        $baseYear = (int) $years[0];

        if ($semester === 'Ganjil') {
            $startDate = Carbon::create($baseYear, 7, 1)->startOfDay();
            $endDate = Carbon::create($baseYear, 12, 31)->endOfDay();
            $months = [7, 8, 9, 10, 11, 12];
        } else {
            $startDate = Carbon::create($baseYear + 1, 1, 1)->startOfDay();
            $endDate = Carbon::create($baseYear + 1, 6, 30)->endOfDay();
            $months = [1, 2, 3, 4, 5, 6];
        }

        $guruId = Auth::id();
        $stats = [
            'total_overall_santri' => StudentAssignment::where('guru_id', $guruId)->distinct('student_id')->count('student_id'),
            'total_santri_diampu'  => StudentAssignment::where('guru_id', $guruId)->where('academic_year', $academicYear)->count(),
        ];

        $recent_activities = RiwayatHafalan::with('student')
            ->where('guru_id', $guruId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();


        $students = Student::where('guru_id', $guruId)
            ->with([
                'targets',
                'memorizations' => fn($q) => $q->select('id', 'student_id', 'surah', 'ayat', 'juz', 'status', 'is_present', 'tanggal')
            ])
            ->get();

        $early_warnings = collect();
        $top_targets = collect();

        foreach ($students as $student) {
            $latestMem = $student->memorizations->sortByDesc('tanggal')->first();
            if (
                !$latestMem ||
                Carbon::parse($latestMem->tanggal)->diffInDays(now()) >= 3 ||
                $latestMem->status === 'Perlu Perbaikan'
            ) {
                $student->warning_reason = !$latestMem ? 'Belum Ada Setoran' : ($latestMem->status === 'Perlu Perbaikan' ? 'Perlu Perbaikan' : 'Lama Tidak Setor (≥ 3 Hari)');
                $student->last_mem_date = $latestMem ? Carbon::parse($latestMem->tanggal) : null;
                $early_warnings->push($student);
            }

            $activeTarget = $student->activeTarget();
            $student->progress_percent = $student->target_progress;
            $top_targets->push($student);
        }

        $early_warnings = $early_warnings->take(5);
        $top_targets = $top_targets->sortByDesc('progress_percent')->take(5);

        return view('guru.dashboard', compact(
            'stats',
            'recent_activities',
            'early_warnings',
            'top_targets',
            'academicYear',
            'semester'
        ));
    }

    public function replyMessage(Request $request, Pesan $pesan)
    {
        $request->validate(['message' => 'required|string']);

        // Find the parent's ID in this conversation. 
        // We can look for the original sender of the thread or just find an entry that isn't the Guru.
        $parentId = ($pesan->sender_id == Auth::id()) ? $pesan->receiver_id : $pesan->sender_id;

        $p = Pesan::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $parentId,
            'student_id' => $pesan->student_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($p))->toOthers();

        // Mark incoming messages as read when teacher replies
        Pesan::where('student_id', $pesan->student_id)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $p]);
        }

        return back()->with('success', 'Balasan pesan berhasil dikirim.');
    }

    public function destroyMessage(Pesan $pesan)
    {
        if ($pesan->receiver_id !== Auth::id()) {
            abort(403);
        }

        Pesan::where('student_id', $pesan->student_id)
            ->where(function ($q) use ($pesan) {
                $q->where('sender_id', $pesan->sender_id)->where('receiver_id', Auth::id())
                    ->orWhere('sender_id', Auth::id())->where('receiver_id', $pesan->sender_id);
            })
            ->update(['is_resolved' => true]);

        return back()->with('success', 'Percakapan telah diselesaikan dan dipindahkan dari antrean.');
    }

    public function markAsRead(Student $student)
    {
        Pesan::where('student_id', $student->id)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function messages(Request $request)
    {
        $guruId = Auth::id();
        $search = $request->input('search');
        $status = $request->input('status'); // all, read, unread
        $showArchive = $request->boolean('archive', false);

        // Calculate current academic year (matching dashboard logic)
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $currentAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);

        // Get student IDs active in current academic year for this teacher
        $activeInCurrentYear = StudentAssignment::where('academic_year', $currentAcademicYear)
            ->where('guru_id', $guruId)
            ->pluck('student_id')
            ->toArray();

        // Base query for unique student message threads
        $queryStudentIds = Pesan::where('receiver_id', $guruId)
            ->where('is_resolved', false);

        // Archive / Academic Year Filter - Logic: show threads for students based on active year assignment
        if ($showArchive) {
            $queryStudentIds->whereNotIn('student_id', $activeInCurrentYear);
        } else {
            $queryStudentIds->whereIn('student_id', $activeInCurrentYear);
        }

        if ($search) {
            $queryStudentIds->where(function ($q) use ($search) {
                $q->whereHas('sender', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhereHas('student', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                });
            });
        }

        $studentIds = $queryStudentIds->distinct()->pluck('student_id');
        $parent_messages = collect();

        foreach ($studentIds as $sId) {
            $hasUnread = Pesan::where('student_id', $sId)
                ->where('receiver_id', $guruId)
                ->where('is_read', false)
                ->exists();

            if ($status === 'read' && $hasUnread) continue;
            if ($status === 'unread' && !$hasUnread) continue;

            $latestInThread = Pesan::with(['sender', 'student'])
                ->where('student_id', $sId)
                ->where(function ($q) use ($guruId) {
                    $q->where('receiver_id', $guruId)->orWhere('sender_id', $guruId);
                })
                ->where('is_resolved', false)
                ->latest()
                ->first();

            if ($latestInThread) {
                $latestInThread->has_unread = $hasUnread;
                $latestInThread->conversation = Pesan::where('student_id', $sId)
                    ->where(function ($q) use ($guruId) {
                        $q->where('sender_id', $guruId)->orWhere('receiver_id', $guruId);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();

                $parent_messages->push($latestInThread);
            }
        }

        $parent_messages = $parent_messages->sortByDesc(fn($msg) => $msg->created_at);

        if ($request->ajax()) {
            return view('guru.messages.partials.list', compact('parent_messages'))->render();
        }

        return view('guru.messages.index', compact('parent_messages', 'showArchive'));
    }
}
