<?php

use App\Http\Controllers\KraepelinTestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Switch Admin Logic
Route::get('/switch-to-admin', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/admin/login');
})->name('switch.to.admin');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('psikotes.dashboard');
    })->name('dashboard');

    Route::get('/psikotes', function () {
        return view('psikotes.dashboard');
    })->name('psikotes.dashboard');

    // GROUP KRAEPELIN
    Route::prefix('psikotes/kraepelin')->name('kraepelin.')->group(function () {
        
        // 1. Statis (Tanpa {session})
        Route::get('/', [KraepelinTestController::class, 'index'])->name('index');
        Route::get('/start', [KraepelinTestController::class, 'start'])->name('start');
        Route::post('/answer', [KraepelinTestController::class, 'saveAnswer'])->name('answer');
        Route::get('/debug/col/{col}', [KraepelinTestController::class, 'debugColumn'])->name('debug.col');

        // 2. Dinamis (Dengan {session})
        // Export ditaruh di sini agar rapi
        Route::get('/{session}/export', [KraepelinTestController::class, 'exportPdf'])->name('export');
        Route::get('/{session}/finish', [KraepelinTestController::class, 'finish'])->name('finish');
        Route::get('/{session}/hasil', [KraepelinTestController::class, 'result'])->name('result');
        Route::post('/{session}/reset', [KraepelinTestController::class, 'resetTest'])->name('reset');
        Route::delete('/{session}/cancel', [KraepelinTestController::class, 'cancel'])->name('cancel');
        
        // Wildcard paling bawah
        Route::get('/{session}', [KraepelinTestController::class, 'show'])->name('show');
    });
});

require __DIR__.'/auth.php';