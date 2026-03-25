<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = \App\Models\Student::with('parent')->latest()->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $parents = \App\Models\User::where('role', 'orang_tua')->get();
        return view('admin.students.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis',
            'parent_id' => 'required|exists:users,id',
        ]);

        \App\Models\Student::create($request->all());

        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil ditambahkan.');
    }
}
