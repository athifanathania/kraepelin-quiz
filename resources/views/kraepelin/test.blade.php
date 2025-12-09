<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tes Kraepelin - Sesi #{{ $session->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- nanti kalau sudah siap Tailwind/Vite, kita bisa pakai @vite --}}
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;   
            margin: 2rem auto;
            background: white;
            border-radius: 12px;
            padding: 1.5rem 2.5rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
        h1 {
            margin-top: 0;
            margin-bottom: .25rem;
        }
        .badge {
            display: inline-block;
            padding: .15rem .5rem;
            border-radius: 999px;
            font-size: .75rem;
            background: #fffbeb;
            color: #92400e;
        }
        .meta {
            font-size: .875rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }
        .box {
            padding: 1rem;
            border-radius: .75rem;
            background: #f9fafb;
            border: 1px dashed #e5e7eb;
            font-size: .9rem;
        }
        a.button {
            display: inline-block;
            margin-top: 1rem;
            padding: .5rem 1rem;
            border-radius: .5rem;
            background: #f97316;
            color: white;
            text-decoration: none;
            font-size: .9rem;
        }
        .kraepelin-grid {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 1rem;
            width: 100%;
        }
        .kraepelin-col {
            min-width: 40px;    
            text-align: left;
        }
        .kraepelin-cell {
            margin: .25rem 0;
        }

        .kraepelin-num {
            font-size: 1.1rem;
            font-weight: bold;
            line-height: 1.1;
            text-align: left;
        }

        .kraepelin-input {
            width: 28px;
            height: 28px;
            margin: 2px 0;
            text-align: center;
            font-size: 1rem;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            outline: none;
        }

        .kraepelin-input:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 1px #fed7aa;
        }
        .kraepelin-col.disabled .kraepelin-input {
            background: #e5e7eb;
            color: #6b7280;
        }

        .kraepelin-col.disabled .kraepelin-input:focus {
            outline: none;
            box-shadow: none;
            border-color: #d1d5db;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>{{ $test->name }}</h1>
    <div class="meta">
        Sesi ID: <strong>#{{ $session->id }}</strong><br>
        Status: <span class="badge">{{ $session->status }}</span><br>
        Dimulai: {{ $session->started_at?->format('d-m-Y H:i:s') ?? '-' }}
    </div>

    <div class="box">
        <p>
            Ini adalah halaman tes Kraepelin untuk sesi ini.
        </p>
        <p>
            Untuk saat ini, kita baru menguji <strong>alur pembuatan sesi & tampilan dasar</strong>.
        </p>
        <p>
            Langkah berikutnya baru kita isi dengan:
        </p>
        <ul>
            <li>Grid angka (kolom & baris)</li>
            <li>Input 1 digit yang lompat otomatis ke atas</li>
            <li>Timer per kolom</li>
        </ul>
    </div>
    <hr style="margin: 2rem 0">

        <div style="margin-bottom: 1rem; display:flex; gap:1.5rem; align-items:center;">
            <div>
                <strong>Kolom aktif:</strong>
                <span id="active-column-label">Kolom 1</span>
                <span style="font-size:.8rem; color:#6b7280;">
                    (dari {{ $columns }} kolom)
                </span>
            </div>
            <div>
                <strong>Sisa waktu kolom:</strong>
                <span id="column-timer">00:{{ str_pad($secondsPerColumn, 2, '0', STR_PAD_LEFT) }}</span>
                <span style="font-size:.8rem; color:#6b7280;">detik</span>
            </div>
        </div>

    <h2>Test Kraepelin</h2>

    <div class="kraepelin-grid">
        @for ($colIndex = 1; $colIndex <= $columns; $colIndex++)
            @php
                $column = $grid[$colIndex] ?? [];

                // cari max row di kolom ini (harusnya 27)
                $rowsInCol = array_keys($column);
                $maxRow    = !empty($rowsInCol) ? max($rowsInCol) : 0;

                // bangun 28 digit: index 0 = paling bawah, 27 = paling atas
                $digitsBottomToTop = [];
                if ($maxRow > 0) {
                    for ($r = 1; $r <= $maxRow; $r++) {
                        $digitsBottomToTop[] = $column[$r]['bottom'];
                    }
                    $digitsBottomToTop[] = $column[$maxRow]['top'];
                }
            @endphp

            <div class="kraepelin-col">
                @if ($maxRow > 0)
                    {{-- i = 27..0 (top → bottom) --}}
                    @for ($i = $maxRow; $i >= 0; $i--)
                        <div class="kraepelin-cell">
                            {{-- angka --}}
                            <div class="kraepelin-num">
                                {{ $digitsBottomToTop[$i] }}
                            </div>

                            {{-- kotak di bawah angka, KECUALI kalau ini angka paling bawah (i = 0) --}}
                            @if ($i > 0)
                                <input
                                    type="text"
                                    maxlength="1"
                                    inputmode="numeric"
                                    class="kraepelin-input"
                                    data-col="{{ $colIndex }}"
                                    data-row="{{ $i }}"    {{-- row_index = i (1..27) --}}
                                    id="cell-{{ $colIndex }}-{{ $i }}"
                                    value="{{ $column[$i]['user_answer'] ?? '' }}"
                                >
                            @endif
                        </div>
                    @endfor
                @endif
            </div>
        @endfor
    </div>

    <hr style="margin: 2rem 0">

    <a href="{{ route('kraepelin.finish', $session) }}" class="button">
        Selesaikan tes &amp; lihat hasil
    </a>

    <a href="{{ route('kraepelin.start') }}" class="button" style="background: #6b7280; margin-left: .5rem;">
        Mulai sesi baru lagi (test)
    </a>
</div>
<script>
    // Fungsi untuk cari input berdasarkan kolom dan baris
    function getCellInput(col, row) {
        return document.querySelector(
            '.kraepelin-input[data-col="' + col + '"][data-row="' + row + '"]'
        );
    }

    function sendAnswerToServer(col, row, value) {
        fetch("{{ route('kraepelin.answer') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({
                session_id: {{ $session->id }},
                col: col,
                row: row,
                answer: value === '' ? null : Number(value),
            }),
        }).catch(() => {
            // untuk sekarang kalau error kita abaikan dulu
            console.error('Gagal kirim jawaban ke server');
        });
    }

        let activeCol = 1;
    let maxCol = {{ $columns }};
    let secondsPerColumn = {{ $secondsPerColumn }};
    let secondsLeft = secondsPerColumn;
    let timerInterval = null;

    function updateTimerLabel() {
        const timerEl = document.getElementById('column-timer');
        if (!timerEl) return;

        const sec = secondsLeft;
        const mm = '00';
        const ss = String(sec).padStart(2, '0');
        timerEl.textContent = `${mm}:${ss}`;
    }

    function updateActiveColumnLabel() {
        const colLabel = document.getElementById('active-column-label');
        if (colLabel) {
            colLabel.textContent = `Kolom ${activeCol}`;
        }
    }

    function disableColumn(col) {
        document
            .querySelectorAll('.kraepelin-input[data-col="' + col + '"]')
            .forEach(function (input) {
                input.disabled = true;
            });

        const colWrapper = document.querySelector('.kraepelin-col:nth-child(' + col + ')');
        if (colWrapper) {
            colWrapper.classList.add('disabled');
        }
    }

    function focusBottomCellOfColumn(col) {
        let row = 1;
        const bottomCell = getCellInput(col, row);
        if (bottomCell && !bottomCell.disabled) {
            bottomCell.focus();
        }
    }

    function moveToNextColumn() {
        // Bekukan kolom aktif sekarang
        disableColumn(activeCol);

        // Pindah kolom
        if (activeCol < maxCol) {
            activeCol++;
            secondsLeft = secondsPerColumn;
            updateActiveColumnLabel();
            updateTimerLabel();
            focusBottomCellOfColumn(activeCol);
        } else {
            clearInterval(timerInterval);
            timerInterval = null;

            // Auto redirect ke finish
            window.location.href = "{{ route('kraepelin.finish', $session) }}";
        }
    }

    function startColumnTimer() {
        updateActiveColumnLabel();
        updateTimerLabel();
        focusBottomCellOfColumn(activeCol);

        if (timerInterval) {
            clearInterval(timerInterval);
        }

        timerInterval = setInterval(function () {
            if (secondsLeft > 0) {
                secondsLeft--;
                updateTimerLabel();
            } else {
                moveToNextColumn();
            }
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.kraepelin-input');

        inputs.forEach(function (input) {

            // Kalau user mengetik sesuatu
            input.addEventListener('input', function (e) {
                let value = input.value;

                // Hanya boleh angka 0-9
                value = value.replace(/[^0-9]/g, '');

                // Trim ke 1 digit
                if (value.length > 1) {
                    value = value.slice(0, 1);
                }

                input.value = value;

                const col = parseInt(input.dataset.col, 10);
                const row = parseInt(input.dataset.row, 10);

                // Kirim ke server (boleh kosong atau 1 digit)
                sendAnswerToServer(col, row, value);

                // Kalau sudah terisi 1 digit, lompat ke baris di atasnya
                if (value.length === 1) {
                    const next = getCellInput(col, row + 1);
                    if (next) {
                        next.focus();
                    }
                }
            });

            // Logika backspace untuk mundur
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace') {
                    const currentValue = input.value;

                    // Kalau masih ada isi → hapus dulu, jangan pindah
                    if (currentValue !== '') {
                        return;
                    }

                    // Kalau sudah kosong dan backspace ditekan → mundur ke baris sebelumnya
                    const col = parseInt(input.dataset.col, 10);
                    const row = parseInt(input.dataset.row, 10);
                    const prev = getCellInput(col, row - 1);

                    if (prev) {
                        e.preventDefault();
                        prev.focus();
                    }
                }
            });
        });

        startColumnTimer();
    });
</script>
</body>
</html>
