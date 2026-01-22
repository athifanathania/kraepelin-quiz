<?php

use App\Http\Controllers\KraepelinTestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. HALAMAN UTAMA (ROOT)
// Menampilkan halaman Welcome (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// 2. REDIRECT DEFAULT BREEZE
// /dashboard diarahkan ke dashboard psikotes
Route::get('/dashboard', function () {
    return redirect()->route('psikotes.dashboard');
})->middleware(['auth'])->name('dashboard');

// 3. LOGIC PINDAH KE ADMIN (LOGOUT USER BIASA)
Route::get('/switch-to-admin', function () {
    Auth::logout(); // Logout user saat ini
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    return redirect('/admin/login'); // Arahkan ke login admin
})->name('switch.to.admin');


// 4. ROUTE YANG WAJIB LOGIN (USER AREA)
Route::middleware('auth')->group(function () {

    // DASHBOARD PSIKOTES (Halaman Selamat Datang)
    Route::get('/psikotes', function () {
        return view('psikotes.dashboard');
    })->name('psikotes.dashboard');

    // GROUP ROUTE KRAEPELIN
    Route::prefix('psikotes')->group(function () {
        
        Route::get('/kraepelin', [KraepelinTestController::class, 'index'])
            ->name('kraepelin.index');

        Route::get('/kraepelin/start', [KraepelinTestController::class, 'start'])
            ->name('kraepelin.start');

        Route::get('/kraepelin/{session}', [KraepelinTestController::class, 'show'])
            ->name('kraepelin.show');

        Route::post('/kraepelin/answer', [KraepelinTestController::class, 'saveAnswer'])
            ->name('kraepelin.answer');

        Route::get('/kraepelin/{session}/finish', [KraepelinTestController::class, 'finish'])
            ->name('kraepelin.finish');

        Route::get('/kraepelin/{session}/hasil', [KraepelinTestController::class, 'result'])
            ->name('kraepelin.result');

        // Route Debug (Saya masukkan ke sini biar aman, hanya user login yg bisa akses)
        Route::get('/kraepelin/debug/col/{col}', [KraepelinTestController::class, 'debugColumn'])
            ->name('kraepelin.debug.col');
        Route::post('/kraepelin/{session}/reset', [KraepelinTestController::class, 'resetTest'])->name('kraepelin.reset');
        Route::delete('/test/{session}/cancel', [KraepelinTestController::class, 'cancel'])->name('kraepelin.cancel');
    });
});

// Route auth dari Breeze
require __DIR__.'/auth.php';