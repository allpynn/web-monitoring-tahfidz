<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController as AdminGuruController;
use App\Http\Controllers\Admin\ParentController as AdminParentController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\HafalanController as GuruHafalanController;
use App\Http\Controllers\Guru\StudentController as GuruStudentController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;
use App\Http\Controllers\Parent\HistoryController as ParentHistoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $user = Auth::user();
    $role = $user?->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru' => redirect()->route('guru.dashboard'),
        'orang_tua' => redirect()->route('parent.dashboard'),
        default => redirect()->route('home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('students', AdminStudentController::class);
        Route::get('/students/{student}/export-pdf', [AdminStudentController::class, 'exportPdf'])->name('students.export');

        Route::resource('users', AdminUserController::class);
        Route::resource('guru', AdminGuruController::class);
        Route::resource('parents', AdminParentController::class);
        Route::post('/import', [AdminDashboardController::class, 'import'])->name('import');
    });

    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::resource('hafalan', GuruHafalanController::class)->only(['index', 'create', 'store']);
        Route::get('/hafalan/export/{student}', [GuruHafalanController::class, 'exportPdf'])->name('hafalan.export');
        Route::resource('students', GuruStudentController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
        Route::get('/students/{student}/export-pdf', [GuruStudentController::class, 'exportPdf'])->name('students.export');
        Route::get('/messages', [GuruDashboardController::class, 'messages'])->name('messages');
        Route::post('/messages/{pesan}/reply', [GuruDashboardController::class, 'replyMessage'])->name('messages.reply');
        Route::post('/messages/{student}/read', [GuruDashboardController::class, 'markAsRead'])->name('messages.read');
    });

    Route::middleware(['role:orang_tua'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
        Route::post('/messages/{student}', [ParentDashboardController::class, 'sendMessage'])->name('messages.send');
        Route::get('/history', [ParentHistoryController::class, 'index'])->name('history.index');
        Route::get('/history/{student}/export-pdf', [ParentHistoryController::class, 'exportPdf'])->name('history.export');
    });
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

