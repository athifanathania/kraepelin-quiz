<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <title>Tes Kraepelin - Sesi #{{ $session->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font: Poppins & Mono untuk Angka --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        /* Hilangkan spinner pada input type number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Custom Scrollbar untuk Grid */
        .kraepelin-container::-webkit-scrollbar {
            height: 8px;
        }
        .kraepelin-container::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .kraepelin-container::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        div:where(.swal2-container) div:where(.swal2-popup) {
            font-family: 'Poppins', sans-serif !important;
            border-radius: 1rem !important; /* Membuat sudut popup lebih bulat (rounded-xl) */
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col pb-20">

    {{-- LOGIC PHP UNTUK MENENTUKAN START COLUMN SAAT RELOAD --}}
    @php
        $lastFilledCol = 1;
        
        // 1. Cari kolom terjauh yang pernah diisi
        if(isset($grid) && is_array($grid)) {
            foreach($grid as $cIndex => $rows) {
                foreach($rows as $rData) {
                    if(isset($rData['user_answer']) && $rData['user_answer'] !== null && $rData['user_answer'] !== '') {
                        $lastFilledCol = $cIndex;
                        break; // Cukup ketemu satu angka di kolom ini, berarti ini kolom aktif
                    }
                }
            }
        }
        
        // 2. Set Start Column ke kolom terakhir tersebut
        $startCol = $lastFilledCol;
    @endphp

    {{-- STICKY HEADER (HUD) --}}
    <header class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            
            {{-- Info Kolom --}}
            <div class="flex flex-col">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Progress</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-bold text-slate-800" id="active-column-label">Kolom 1</span>
                    <span class="text-xs text-slate-500 font-medium">/ {{ $columns }}</span>
                </div>
            </div>

            {{-- Timer Besar --}}
            <div class="flex flex-col items-end">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Sisa Waktu</span>
                <div class="flex items-center gap-2">
                    <div class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </div>
                    <span class="text-2xl font-mono font-bold text-red-600 tabular-nums leading-none" id="column-timer">
                        00:{{ str_pad($secondsPerColumn, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>

        </div>
        
        {{-- Progress Bar Visual (Garis tipis di bawah header) --}}
        <div class="h-1 w-full bg-slate-100">
            <div id="overall-progress" class="h-full bg-orange-500 transition-all duration-500 ease-linear" style="width: 0%"></div>
        </div>
    </header>

    {{-- INFO SECTION (Hanya muncul di awal atau bisa di-dismiss) --}}
    <div class="pt-24 px-4 max-w-4xl mx-auto mb-6">
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-4 items-start shadow-sm">
            <div class="flex-shrink-0 mt-1">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-1">Instruksi Singkat:</p>
                <ul class="list-disc list-inside space-y-1 text-blue-700/80">
                    <li>Jumlahkan dua angka yang berdekatan.</li>
                    <li>Ketik <strong>hanya digit terakhir</strong> (satuan). Contoh: 8 + 6 = 14, ketik <strong>4</strong>.</li>
                    <li>Tekan angka untuk mengisi, kursor akan otomatis naik.</li>
                    <li>Gunakan <strong>Backspace</strong> untuk koreksi (turun ke bawah).</li>
                    <li>Jika waktu habis, kolom akan terkunci dan pindah otomatis ke sebelah kanan.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- MAIN GRID CONTAINER --}}
    <main class="flex-1 w-full max-w-[95vw] mx-auto overflow-hidden bg-slate-200/50 rounded-2xl border border-slate-300 shadow-inner relative">
        
        {{-- Scrollable Area --}}
        <div class="kraepelin-container w-full overflow-x-auto overflow-y-hidden pb-4 pt-4 px-4 scroll-smooth" id="grid-scroll-area">
            <div class="flex gap-2 w-max mx-auto min-w-full justify-center"> @for ($colIndex = 1; $colIndex <= $columns; $colIndex++)
                    @php
                        $column = $grid[$colIndex] ?? [];
                        $rowsInCol = array_keys($column);
                        $maxRow    = !empty($rowsInCol) ? max($rowsInCol) : 0;
                        
                        // Siapkan data 28 digit bottom -> top
                        $digitsBottomToTop = [];
                        if ($maxRow > 0) {
                            for ($r = 1; $r <= $maxRow; $r++) {
                                $digitsBottomToTop[] = $column[$r]['bottom'];
                            }
                            $digitsBottomToTop[] = $column[$maxRow]['top']; // Angka puncak
                        }
                    @endphp

                    {{-- KOLOM WRAPPER --}}
                    <div class="kraepelin-col group transition-all duration-300 ease-in-out rounded-lg p-1 flex flex-col items-center min-w-[36px]
                        {{ $colIndex === 1 ? 'bg-white shadow-md ring-1 ring-orange-500 z-10 scale-100 opacity-100' : 'bg-slate-300/40 opacity-50 grayscale scale-95' }}"
                        id="col-wrapper-{{ $colIndex }}"
                        data-col="{{ $colIndex }}">
                        
                        {{-- Header Kolom Kecil --}}
                        <div class="mb-2 text-[10px] font-bold text-slate-400">
                            COL {{ $colIndex }}
                        </div>

                        @if ($maxRow > 0)
                            {{-- Loop dari Atas ke Bawah (Display purposes) --}}
                            @for ($i = $maxRow; $i >= 0; $i--)
                                <div class="flex flex-col items-center">
                                    
                                    {{-- ANGKA SOAL --}}
                                    <div class="h-6 flex items-center justify-center text-base font-mono font-bold text-slate-700 select-none">
                                        {{ $digitsBottomToTop[$i] }}
                                    </div>

                                    {{-- INPUT KOTAK (Di antara angka, kecuali angka paling bawah) --}}
                                    @if ($i > 0)
                                        <div class="my-0.5 relative">
                                            <input
                                                type="text"
                                                maxlength="1"
                                                inputmode="numeric"
                                                autocomplete="off"
                                                class="kraepelin-input 
                                                    w-8 h-8                  {{-- Ukuran compact 32px --}}
                                                    text-center              {{-- Teks rata tengah --}}
                                                    text-base font-bold      {{-- Ukuran font pas --}}
                                                    leading-none             {{-- Hilangkan jarak baris --}}
                                                    p-0                      {{-- Hapus padding browser --}}
                                                    border border-slate-300 rounded shadow-sm bg-white text-slate-800
                                                    transition-all duration-100
                                                    focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10
                                                    disabled:bg-slate-200 disabled:text-slate-400 disabled:border-transparent disabled:shadow-none"
                                                
                                                data-col="{{ $colIndex }}"
                                                data-row="{{ $i }}" 
                                                id="cell-{{ $colIndex }}-{{ $i }}"

                                                {{-- LOGIC RESET: Jika ini kolom aktif, kosongkan value. Jika kolom lama, tampilkan jawaban --}}
                                                value="{{ $colIndex === $startCol ? '' : ($column[$i]['user_answer'] ?? '') }}"
                                                
                                                {{-- Disable input jika bukan kolom aktif --}}
                                                {{ $colIndex !== $startCol ? 'disabled' : '' }} 
                                            >
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    </main>

    {{-- FOOTER ACTIONS --}}
    <footer class="mt-8 mb-8 text-center px-4 space-y-4">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            
            {{-- Tombol Selesaikan (Tetap, atau mau dipercantik juga boleh dengan cara serupa) --}}
            <a href="{{ route('kraepelin.finish', $session) }}" 
            class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 hover:text-slate-900 transition shadow-sm w-full sm:w-auto"
            onclick="event.preventDefault(); confirmFinish(this.href);">
                Selesaikan Tes
            </a>
            
            {{-- TOMBOL RESET --}}
            {{-- Beri ID pada form agar bisa di-submit lewat JS --}}
            <form id="form-reset-test" action="{{ route('kraepelin.reset', $session->id) }}" method="POST" class="w-full sm:w-auto">
                @csrf
                {{-- Ubah type="button" agar tidak langsung submit --}}
                {{-- Hapus onclick="return confirm..." yang lama --}}
                {{-- Tambahkan onclick="confirmReset()" --}}
                <button type="button" 
                        onclick="confirmReset()"
                        class="w-full sm:w-auto px-6 py-3 bg-slate-200 text-slate-600 font-medium rounded-xl hover:bg-slate-300 hover:text-red-600 hover:bg-red-50 transition text-xs shadow-sm">
                    Reset / Ulang (Dev)
                </button>
            </form>

        </div>
    </footer>

    {{-- SCRIPT --}}
    <script>
        // FUNGSI UNTUK TOMBOL RESET
        function confirmReset() {
            Swal.fire({
                title: 'Ulangi Tes dari Awal?',
                text: "Seluruh jawaban pada sesi ini akan dihapus dan kembali ke Kolom 1.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // Warna Merah (Tailwind red-500)
                cancelButtonColor: '#64748b',  // Warna Abu (Tailwind slate-500)
                confirmButtonText: 'Ya, Reset Sekarang!',
                cancelButtonText: 'Batal',
                reverseButtons: true, // Tombol batal di kiri, hapus di kanan
                background: '#fff',
                color: '#1e293b' // Slate-800
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user klik Ya, submit form secara manual
                    document.getElementById('form-reset-test').submit();
                }
            });
        }

        // (OPSIONAL) FUNGSI UNTUK TOMBOL SELESAI
        function confirmFinish(url) {
            Swal.fire({
                title: 'Selesaikan Tes?',
                text: "Waktu akan dihentikan dan skor Anda akan disimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f97316', // Orange (sesuai tema Kraepelin)
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Lanjut Mengerjakan'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        // (OPSIONAL) MENAMPILKAN FLASH MESSAGE 'SUCCESS' DARI CONTROLLER
        // Tambahkan ini agar setelah reset, muncul notifikasi kecil sukses
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            })
        @endif
        
        const maxCol = {{ $columns }};
        const secondsPerColumn = {{ $secondsPerColumn }};
        let activeCol = {{ $startCol }};
        let secondsLeft = secondsPerColumn;
        let timerInterval = null;

        // --- DOM Elements ---
        const timerEl = document.getElementById('column-timer');
        const colLabel = document.getElementById('active-column-label');
        const scrollArea = document.getElementById('grid-scroll-area');
        const progressBar = document.getElementById('overall-progress');

        // --- Logic ---

        function updateTimerLabel() {
            if (!timerEl) return;
            const ss = String(secondsLeft).padStart(2, '0');
            timerEl.textContent = `00:${ss}`;
            
            // Visual Alert warna merah kedip jika waktu < 5 detik
            if (secondsLeft <= 5) {
                timerEl.classList.add('text-red-600', 'scale-110');
                timerEl.parentElement.classList.add('animate-pulse');
            } else {
                timerEl.classList.remove('text-red-600', 'scale-110');
                timerEl.parentElement.classList.remove('animate-pulse');
                timerEl.classList.add('text-slate-700');
            }
        }

        function updateActiveColumnLabel() {
            if (colLabel) colLabel.textContent = `Kolom ${activeCol}`;
            
            // Update Progress Bar Atas
            const percent = ((activeCol - 1) / maxCol) * 100;
            if(progressBar) progressBar.style.width = `${percent}%`;
        }

        function updateColumnVisuals() {
            for (let c = 1; c <= maxCol; c++) {
                const wrapper = document.getElementById(`col-wrapper-${c}`);
                if (!wrapper) continue;

                // Reset Class dasar
                wrapper.classList.remove(
                    'bg-white', 'shadow-lg', 'ring-2', 'ring-orange-500', 'z-10', 'scale-100', 'opacity-100', // Active style
                    'bg-slate-300/40', 'opacity-50', 'grayscale', 'scale-95' // Inactive style
                );

                if (c === activeCol) {
                    // STYLE: ACTIVE
                    wrapper.classList.add('bg-white', 'shadow-lg', 'ring-2', 'ring-orange-500', 'z-10', 'scale-100', 'opacity-100');
                    
                    // Enable inputs
                    const inputs = wrapper.querySelectorAll('.kraepelin-input');
                    inputs.forEach(input => input.disabled = false);

                } else {
                    // STYLE: INACTIVE
                    wrapper.classList.add('bg-slate-300/40', 'opacity-50', 'grayscale', 'scale-95');
                    
                    // Disable inputs
                    const inputs = wrapper.querySelectorAll('.kraepelin-input');
                    inputs.forEach(input => input.disabled = true);
                }
            }
        }

        function scrollToActiveColumn() {
            const wrapper = document.getElementById(`col-wrapper-${activeCol}`);
            if (wrapper && scrollArea) {
                // Scroll agar kolom aktif berada di tengah layar
                const scrollLeft = wrapper.offsetLeft - (scrollArea.clientWidth / 2) + (wrapper.clientWidth / 2);
                scrollArea.scrollTo({ left: scrollLeft, behavior: 'smooth' });
            }
        }

        function focusBottomCellOfColumn(col) {
            const inputs = document.querySelectorAll(`.kraepelin-input[data-col="${col}"]`);
            const bottomCell = document.querySelector(`.kraepelin-input[data-col="${col}"][data-row="1"]`);
            
            if (bottomCell && !bottomCell.disabled) {
                bottomCell.value = ""; // Pastikan kosong bersih secara JS juga
                setTimeout(() => bottomCell.focus(), 100);
            }
        }

        function moveToNextColumn() {
            if (activeCol < maxCol) {
                activeCol++;
                secondsLeft = secondsPerColumn;
                
                updateActiveColumnLabel();
                updateTimerLabel();
                updateColumnVisuals();
                scrollToActiveColumn();
                focusBottomCellOfColumn(activeCol);
            } else {
                finishTest();
            }
        }

        function finishTest() {
            clearInterval(timerInterval);
            timerInterval = null;
            if(progressBar) progressBar.style.width = '100%';
            window.location.href = "{{ route('kraepelin.finish', $session) }}";
        }

        function startColumnTimer() {
            secondsLeft = secondsPerColumn; 

            updateActiveColumnLabel();
            updateTimerLabel();
            updateColumnVisuals();
            scrollToActiveColumn();
            
            focusBottomCellOfColumn(activeCol);

            if (timerInterval) clearInterval(timerInterval);

            timerInterval = setInterval(function () {
                if (secondsLeft > 0) {
                    secondsLeft--;
                    updateTimerLabel();
                } else {
                    moveToNextColumn();
                }
            }, 1000);
        }

        // --- Input Handling ---
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
            }).catch(console.error);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.kraepelin-input');

            inputs.forEach(function (input) {
                // INPUT EVENT
                input.addEventListener('input', function (e) {
                    let value = input.value.replace(/[^0-9]/g, ''); // Hanya angka
                    if (value.length > 1) value = value.slice(-1); // Ambil digit terakhir
                    
                    input.value = value;

                    const col = parseInt(input.dataset.col, 10);
                    const row = parseInt(input.dataset.row, 10);

                    // Kirim Data
                    sendAnswerToServer(col, row, value);

                    // Auto Move Up
                    if (value.length === 1) {
                        const next = document.querySelector(`.kraepelin-input[data-col="${col}"][data-row="${row + 1}"]`);
                        
                        if (next) {
                            next.focus();

                            // --- BAGIAN BARU DITAMBAHKAN DI SINI ---
                            // Paksa scroll agar input ada di TENGAH layar (tidak ketutup header)
                            setTimeout(() => {
                                next.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center',  // Kuncinya disini: center = tengah
                                    inline: 'nearest'
                                });
                            }, 100); // Delay 100ms agar smooth
                            // ---------------------------------------
                        }
                    }
                });

                // KEYDOWN (BACKSPACE)
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace') {
                        if (input.value !== '') return; // Hapus angka dulu

                        // Pindah ke bawah
                        const col = parseInt(input.dataset.col, 10);
                        const row = parseInt(input.dataset.row, 10);
                        const prev = document.querySelector(`.kraepelin-input[data-col="${col}"][data-row="${row - 1}"]`);
                        
                        if (prev) {
                            e.preventDefault();
                            prev.focus();
                        }
                    }
                });
            });

            // Start Logic
            startColumnTimer();
        });
    </script>
</body>
</html>