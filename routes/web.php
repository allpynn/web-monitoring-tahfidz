<?php


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guru\HafalanController;
use App\Http\Controllers\Parent\HistoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');




Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    $role = $user?->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru' => redirect()->route('guru.dashboard'),
        'orang_tua' => redirect()->route('parent.dashboard'),
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('students', StudentController::class);
        Route::get('/students/{student}/export-pdf', [StudentController::class, 'exportPdf'])->name('students.export');

        Route::resource('users', UserController::class);
        Route::resource('guru', GuruController::class);
        Route::resource('parents', ParentController::class);
        Route::post('/import', [DashboardController::class, 'import'])->name('import');
    });

    // Guru Routes
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('hafalan', HafalanController::class);
        Route::get('/hafalan/export/{student}', [HafalanController::class, 'exportPdf'])->name('hafalan.export');
        Route::resource('students', App\Http\Controllers\Guru\StudentController::class);
        Route::get('/students/{student}/export-pdf', [App\Http\Controllers\Guru\StudentController::class, 'exportPdf'])->name('students.export');
        Route::get('/students/{student}/export-semester-pdf', [HafalanController::class, 'exportSemesterPdf'])->name('students.export_semester');
        Route::post('/messages/{pesan}/reply', [App\Http\Controllers\Guru\DashboardController::class, 'replyMessage'])->name('messages.reply');
    });

    // Parent Routes
    Route::middleware(['role:orang_tua'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Parent\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/messages/{student}', [App\Http\Controllers\Parent\DashboardController::class, 'sendMessage'])->name('messages.send');
        Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
        Route::get('/history/{student}/export-pdf', [HistoryController::class, 'exportPdf'])->name('history.export');
    });
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
