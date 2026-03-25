<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $students = auth()->user()->students()->with(['memorizations' => function($q) {
            $q->latest();
        }])->get();
        
        return view('parent.dashboard', compact('students'));
    }
}
