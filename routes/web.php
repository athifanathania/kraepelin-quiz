<?php

use App\Http\Controllers\KraepelinTestController;
use Illuminate\Support\Facades\Route;

// Root: kalau sudah login → ke dashboard psikotes, kalau belum → login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('psikotes.dashboard')
        : redirect()->route('login');
});

// /dashboard (default Breeze) diarahkan ke dashboard psikotes
Route::get('/dashboard', function () {
    return redirect()->route('psikotes.dashboard');
})->middleware(['auth'])->name('dashboard');

// Semua route di bawah ini wajib login
Route::middleware('auth')->group(function () {

    // DASHBOARD PSIKOTES (halaman selamat datang)
    Route::get('/psikotes', function () {
        return view('psikotes.dashboard');
    })->name('psikotes.dashboard');

    // Prefix URL jadi /psikotes/..., tapi NAMA route tetap kraepelin.*
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
    });
});

// Route debug (opsional)
Route::get('/kraepelin/debug/col/{col}', [KraepelinTestController::class, 'debugColumn'])
    ->name('kraepelin.debug.col');

// Route auth dari Breeze
require __DIR__.'/auth.php';
