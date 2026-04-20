<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function manajemenSantri()
    {
        return view('landing.manajemen-santri');
    }

    public function inputRealtime()
    {
        return view('landing.input-realtime');
    }

    public function laporanVisual()
    {
        return view('landing.laporan-visual');
    }
}
