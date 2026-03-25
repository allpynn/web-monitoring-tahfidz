<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_hafalan' => \App\Models\Memorization::where('guru_id', auth()->id())->where('is_present', true)->count(),
            'total_absensi' => \App\Models\Memorization::where('guru_id', auth()->id())->count(),
            'today_entries' => \App\Models\Memorization::where('guru_id', auth()->id())->whereDate('created_at', today())->count(),
        ];
        
        return view('guru.dashboard', compact('stats'));
    }
}
