<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $guruCount = \App\Models\User::where('role', 'guru')->count();
        $studentCount = \App\Models\Student::count();
        $hafalanCount = \App\Models\Memorization::count();
        $lancarPercent = $hafalanCount > 0 ? round((\App\Models\Memorization::where('status', 'Lancar')->count() / $hafalanCount) * 100) : 0;

        return view('admin.dashboard', compact('guruCount', 'studentCount', 'hafalanCount', 'lancarPercent'));
    }
}
