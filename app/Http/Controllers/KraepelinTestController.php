<?php


namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestSession;
use App\Models\KraepelinAnswer;
use Illuminate\Http\Request;
use App\Support\KraepelinTemplate;

class KraepelinTestController extends Controller
{
    // --- START SESSION / GENERATE ANGKA ---

    public function start(Request $request)
    {
        $userId = auth()->id();
        if (! $userId) {
            abort(401);
        }

        $test = Test::where('code', 'KRAEPELIN')->firstOrFail();

        // 1) Cek apakah ada sesi in_progress → kalau ada, langsung lanjut ke sesi itu
        $inProgress = TestSession::where('user_id', $userId)
            ->where('test_id', $test->id)
            ->where('status', 'in_progress')
            ->latest('id')
            ->first();

        if ($inProgress) {
            return redirect()->route('kraepelin.show', $inProgress);
        }

        // 2) Cek sesi terakhir yang sudah FINISHED
        $lastFinished = TestSession::where('user_id', $userId)
            ->where('test_id', $test->id)
            ->where('status', 'finished')
            ->latest('finished_at')
            ->first();

        // Jika pernah selesai dan TIDAK diizinkan retake → blok
        if ($lastFinished && ! $lastFinished->can_retake) {
            return redirect()
                ->route('psikotes.dashboard')
                ->with('status', 'Kamu sudah menyelesaikan Tes Kraepelin. '
                    .'Jika perlu mengulang, silakan hubungi HRD / Admin.');
        }

        // 3) Kalau lolos cek di atas → buat sesi baru
        $session = TestSession::create([
            'user_id'    => $userId,
            'test_id'    => $test->id,
            'started_at' => now(),
            'status'     => 'in_progress',
        ]);

        // === GENERATE SOAL DARI TEMPLATE (kode kamu yang lama) ===
        $columns  = 50;
        $digitRows = 28; // 28 angka per kolom di template
        $rows     = 27;  // 27 kotak operasi

        for ($col = 1; $col <= $columns; $col++) {

            $chain = KraepelinTemplate::getColumnChain($col); // 28 angka bottom→top

            for ($row = 1; $row <= $rows; $row++) {
                KraepelinAnswer::create([
                    'test_session_id' => $session->id,
                    'column_index'    => $col,
                    'row_index'       => $row,
                    'bottom_number'   => $chain[$row - 1],
                    'top_number'      => $chain[$row],
                ]);
            }
        }

        return redirect()->route('kraepelin.show', $session);
    }

    // --- DEBUG 1 KOLOM ---

    public function debugColumn(int $col)
    {
        $template = KraepelinTemplate::getColumnChain($col); // 28 angka bottom→top

        $session = TestSession::latest('id')->first();
        $bottoms = [];
        $tops    = [];

        if ($session) {
            $answers = KraepelinAnswer::where('test_session_id', $session->id)
                ->where('column_index', $col)
                ->orderBy('row_index')
                ->get();

            $bottoms = $answers->pluck('bottom_number')->all();
            $tops    = $answers->pluck('top_number')->all();
        }

        return response()->json([
            'kolom'                    => $col,
            'template_bottom_to_top'   => $template,   // index 0..27
            'db_bottom_numbers'        => $bottoms,    // harus = template[0..26]
            'db_top_numbers'           => $tops,       // harus = template[1..27]
        ]);
    }

    public function show(TestSession $session)
    {
        if ($session->test->type !== 'kraepelin') {
            abort(404);
        }

        $this->ensureOwnedByCurrentUser($session);

        if ($session->status === 'finished') {
            return redirect()
                ->route('kraepelin.index')
                ->with('status', 'Tes Kraepelin kamu sudah selesai.');
        }

        // Ambil semua jawaban/angka dari DB
        $answers = $session->kraepelinAnswers()
            ->orderBy('column_index')
            ->orderBy('row_index')
            ->get();

        // Susun ulang jadi grid
        $grid = [];
        foreach ($answers as $answer) {
            $col = $answer->column_index;
            $row = $answer->row_index;

            if (! isset($grid[$col])) {
                $grid[$col] = [];
            }

            $grid[$col][$row] = [
                'top'         => $answer->top_number,
                'bottom'      => $answer->bottom_number,
                'user_answer' => $answer->user_answer,
                'is_correct'  => $answer->is_correct,
            ];
        }

        // Hitung rekap
        $stats         = [];
        $totalAnswered = 0;
        $totalCorrect  = 0;
        $totalWrong    = 0;

        foreach ($answers as $answer) {
            $col = $answer->column_index;

            if (! isset($stats[$col])) {
                $stats[$col] = [
                    'answered' => 0,
                    'correct'  => 0,
                    'wrong'    => 0,
                ];
            }

            if (! is_null($answer->user_answer)) {
                $stats[$col]['answered']++;
                $totalAnswered++;

                if ($answer->is_correct === true) {
                    $stats[$col]['correct']++;
                    $totalCorrect++;
                } elseif ($answer->is_correct === false) {
                    $stats[$col]['wrong']++;
                    $totalWrong++;
                }
            }
        }

        return view('kraepelin.test', [
            'session'       => $session,
            'test'          => $session->test,
            'grid'          => $grid,
            'columns'       => count($grid),
            'rows'          => ! empty($grid) ? count(reset($grid)) : 0,
            'stats'         => $stats,
            'totalAnswered' => $totalAnswered,
            'totalCorrect'  => $totalCorrect,
            'totalWrong'    => $totalWrong,
            'secondsPerColumn' => 15,
        ]);
    }

    public function saveAnswer(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'col'        => 'required|integer',
            'row'        => 'required|integer',
            'answer'     => 'nullable|integer|min:0|max:9',
        ]);

        $userId = auth()->id();

        if (! $userId) {
            return response()->json(['success' => false], 401);
        }

        $session = TestSession::where('id', $request->session_id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $record = KraepelinAnswer::where('test_session_id', $session->id)
            ->where('column_index', $request->col)
            ->where('row_index', $request->row)
            ->firstOrFail();

        if ($request->answer === null || $request->answer === '') {
            $isCorrect = null;
        } else {
            $correctSum = ($record->top_number + $record->bottom_number) % 10;
            $isCorrect  = ((int) $request->answer) === $correctSum ? 1 : 0;
        }

        $record->update([
            'user_answer' => $request->answer,
            'is_correct'  => $isCorrect,
            'answered_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function finish(TestSession $session)
    {
        $this->ensureOwnedByCurrentUser($session);

        if ($session->status === 'finished') {
            return redirect()
                ->route('kraepelin.index')
                ->with('status', 'Tes Kraepelin sudah selesai. Silakan hubungi HR / admin untuk melihat hasil penilaian.');
        }

        $summary = $session->kraepelinAnswers()
            ->selectRaw('
                SUM(CASE WHEN user_answer IS NOT NULL THEN 1 ELSE 0 END) AS answered,
                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END)          AS correct,
                SUM(CASE WHEN is_correct = 0 THEN 1 ELSE 0 END)          AS wrong
            ')
            ->first();

        $answered = (int) ($summary->answered ?? 0);
        $correct  = (int) ($summary->correct  ?? 0);
        $wrong    = (int) ($summary->wrong    ?? 0);
        $accuracy = $answered > 0 ? round($correct / $answered * 100) : 0;

        $session->update([
            'status'         => 'finished',
            'answered_count' => $answered,
            'correct_count'  => $correct,
            'wrong_count'    => $wrong,
            'accuracy'       => $accuracy,
        ]);

        return redirect()
            ->route('kraepelin.index')
            ->with('status', 'Tes Kraepelin sudah selesai. Silakan hubungi HR / admin untuk melihat hasil penilaian.');
    }

    public function result(TestSession $session)
    {
        if ($session->test->type !== 'kraepelin') {
            abort(404);
        }

        $this->ensureOwnedByCurrentUser($session);

        $answers = $session->kraepelinAnswers()
            ->orderBy('column_index')
            ->orderBy('row_index')
            ->get();

        $stats         = [];
        $totalAnswered = 0;
        $totalCorrect  = 0;
        $totalWrong    = 0;

        foreach ($answers as $answer) {
            $col = $answer->column_index;

            if (! isset($stats[$col])) {
                $stats[$col] = [
                    'answered' => 0,
                    'correct'  => 0,
                    'wrong'    => 0,
                ];
            }

            if (! is_null($answer->user_answer)) {
                $stats[$col]['answered']++;
                $totalAnswered++;

                if ($answer->is_correct === true) {
                    $stats[$col]['correct']++;
                    $totalCorrect++;
                } elseif ($answer->is_correct === false) {
                    $stats[$col]['wrong']++;
                    $totalWrong++;
                }
            }
        }

        return view('kraepelin.result', [
            'session'       => $session,
            'test'          => $session->test,
            'stats'         => $stats,
            'totalAnswered' => $totalAnswered,
            'totalCorrect'  => $totalCorrect,
            'totalWrong'    => $totalWrong,
        ]);
    }

    protected function ensureOwnedByCurrentUser(TestSession $session): void
    {
        $userId = auth()->id();

        if (! $userId || $session->user_id !== $userId) {
            abort(403);
        }
    }

    public function index()
    {
        $userId = auth()->id();

        if (! $userId) {
            abort(401);
        }

        $sessions = TestSession::where('user_id', $userId)
            ->whereHas('test', function ($q) {
                $q->where('code', 'KRAEPELIN');
            })
            ->withCount([
                'kraepelinAnswers as answered_count' => function ($q) {
                    $q->whereNotNull('user_answer');
                },
                'kraepelinAnswers as correct_count' => function ($q) {
                    $q->where('is_correct', 1);
                },
                'kraepelinAnswers as wrong_count' => function ($q) {
                    $q->where('is_correct', 0);
                },
            ])
            ->orderByDesc('id')
            ->get();

        return view('kraepelin.index', [
            'sessions' => $sessions,
        ]);
    }

    protected function recalculateSummary(TestSession $session): void
    {
        $answers = $session->kraepelinAnswers()->get();

        $answered = 0;
        $correct  = 0;
        $wrong    = 0;

        foreach ($answers as $answer) {
            if (! is_null($answer->user_answer)) {
                $answered++;

                if ($answer->is_correct === 1 || $answer->is_correct === true) {
                    $correct++;
                } elseif ($answer->is_correct === 0 || $answer->is_correct === false) {
                    $wrong++;
                }
            }
        }

        $accuracy = $answered > 0 ? (int) round(($correct / $answered) * 100) : 0;

        $session->updateQuietly([
            'answered_count' => $answered,
            'correct_count'  => $correct,
            'wrong_count'    => $wrong,
            'accuracy'       => $accuracy,
        ]);
    }

    public function resetTest($sessionId)
    {
        $session = TestSession::findOrFail($sessionId);

        $this->ensureOwnedByCurrentUser($session);

        if ($session->status === 'finished') {
             return redirect()->back()->with('error', 'Tes yang sudah selesai tidak bisa di-reset.');
        }

        \App\Models\KraepelinAnswer::where('test_session_id', $session->id)
            ->update([
                'user_answer' => null,
                'is_correct'  => null,
                'answered_at' => null
            ]);

        return redirect()->route('kraepelin.show', $session->id)
                         ->with('success', 'Tes berhasil di-reset ke awal.');
    }
}
