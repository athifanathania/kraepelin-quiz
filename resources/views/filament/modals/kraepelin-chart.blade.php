@php 
    /** @var \App\Models\TestSession $record */
    // Kita tetap butuh data ini untuk Grafik & Header
    $labels   = $perColumn->pluck('column_index')->map(fn ($i) => 'Kolom '.$i);
    $correct  = $perColumn->pluck('correct');

    $totalAnswered = $perColumn->sum('answered');
    $totalCorrect  = $perColumn->sum('correct');
    $totalWrong    = $perColumn->sum('wrong');

    // Setting Target
    $boxesPerColumn = 27; 
    $totalColumns   = $perColumn->count(); 
    $totalTarget    = $boxesPerColumn * $totalColumns;

    $computedAccuracy = $totalTarget > 0
        ? round(($totalCorrect / $totalTarget) * 100)
        : null;
@endphp

<style>
    .kraepelin-header {
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 1);
        background: linear-gradient(120deg, #f0f9ff, #f9fafb, #ecfdf5);
        padding: 16px 20px 18px 20px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    }
    .kraepelin-header-title {
        font-size: 11px;
        letter-spacing: .08em;
        text-transform: uppercase;
        font-weight: 600;
        color: #6b7280;
    }
    .kraepelin-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 500;
        background: #0f172a;
        color: #f9fafb;
    }
    .kraepelin-chip-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
    }
    .kraepelin-stat-grid {
        display: grid;
        gap: 10px;
    }
    @media (min-width: 768px) {
        .kraepelin-header-main {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(0, 1.1fr);
            gap: 18px;
            align-items: stretch;
        }
        .kraepelin-stat-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (max-width: 767px) {
        .kraepelin-header-main {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 14px;
        }
        .kraepelin-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>

<div
    x-data
    x-init="$nextTick(() => {
        const body = $el.closest('.fi-modal-window-body');
        if (body) body.scrollTo({ top: 0, behavior: 'auto' });
        window.scrollTo({ top: 0, behavior: 'auto' });
    })"
    class="space-y-4"
>
    {{-- ================= HEADER RINGKASAN ================= --}}
    <div class="kraepelin-header">
        {{-- Judul & Chip Akurasi --}}
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="kraepelin-header-title">Hasil Tes Kraepelin</p>
                <p class="mt-0.5 text-sm font-semibold text-slate-900">
                    {{ $record->user?->name ?? '—' }}
                </p>
                <p class="text-[11px] text-slate-500">
                    {{ $record->user?->email ?? '—' }}
                </p>
            </div>
            <div class="flex flex-col items-end gap-1 text-[11px]">
                <span class="kraepelin-chip">
                    <span class="kraepelin-chip-dot"></span>
                    Akurasi 
                    <span class="font-semibold ml-1">
                        {{ $computedAccuracy !== null ? $computedAccuracy.'%' : '—' }}
                    </span>
                </span>
                <span class="text-[10px] text-slate-600">
                    Berdasarkan {{ $totalTarget }} kotak
                </span>
            </div>
        </div>

        {{-- Info Peserta & Stats --}}
        <div class="kraepelin-header-main mt-4">
            {{-- Kiri --}}
            <div class="space-y-3">
                <div class="rounded-xl bg-white/80 px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase">Peserta</p>
                    <p class="mt-0.5 text-[13px] font-semibold text-slate-900">{{ $record->user?->name ?? '—' }}</p>
                </div>
                <div class="rounded-xl bg-white/80 px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase">Tes</p>
                    <p class="mt-0.5 text-[13px] font-semibold text-slate-900">
                        {{ $record->test?->name }} ({{ $record->test?->code }})
                    </p>
                </div>
            </div>

            {{-- Kanan Stats --}}
            <div class="kraepelin-stat-grid text-[11px]">
                {{-- Harus Diisi --}}
                <div class="rounded-xl border border-slate-200 bg-white px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase">Target</p>
                    <p class="mt-0.5 text-lg font-semibold text-slate-900 leading-none">{{ $totalTarget }}</p>
                    <p class="mt-0.5 text-[10px] text-slate-500">Total kotak</p>
                </div>
                {{-- Diisi --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase">Diisi</p>
                    <p class="mt-0.5 text-lg font-semibold text-slate-900 leading-none">{{ $totalAnswered }}</p>
                    <p class="mt-0.5 text-[10px] text-slate-500">Terjawab</p>
                </div>
                {{-- Benar --}}
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-emerald-700 uppercase">Benar</p>
                    <p class="mt-0.5 text-lg font-semibold text-emerald-700 leading-none">{{ $totalCorrect }}</p>
                    <p class="mt-0.5 text-[10px] text-emerald-700/80">Jawaban Tepat</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= GRAFIK ================= --}}
    <div
        class="p-4 rounded-xl bg-white border"
        x-data="{
            labels: @js($labels->values()),
            correct: @js($correct->values()),
        }"
        x-init="
            const ctx = $refs.kraepelinCanvas;
            if (!ctx || !window.Chart) return;
            if (ctx.__kraepelinChartInstance) { ctx.__kraepelinChartInstance.destroy(); }
            
            ctx.__kraepelinChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Benar per kolom',
                        data: correct,
                        tension: 0.3,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6, 
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true 
                    }],
                },
                options: {
                    responsive: true,
                    
                    // --- FITUR HOVER BARU (Tetap ada) ---
                    interaction: {
                        mode: 'index',      
                        intersect: false,   
                    },
                    hover: {
                        mode: 'index',
                        intersect: false
                    },
                    // ------------------------------------

                    scales: {
                        x: { 
                            title: { display: true, text: 'Kolom' },
                            // SAYA HAPUS 'grid: { display: false }' agar garis vertikal muncul lagi
                            grid: { 
                                display: true,
                                color: 'rgba(0,0,0,0.05)' // Warna grid halus
                            } 
                        },
                        y: { 
                            beginAtZero: true, 
                            ticks: { precision: 0 },
                            // SAYA HAPUS 'border: { dash: ... }' agar garis horizontal jadi solid (tegas) lagi
                            grid: { 
                                display: true,
                                color: 'rgba(0,0,0,0.05)' 
                            }
                        },
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            displayColors: false, 
                            backgroundColor: 'rgba(15, 23, 42, 0.9)', 
                            padding: 10,
                            titleFont: { size: 13 },
                            bodyFont: { size: 13, weight: 'bold' }
                        }
                    },
                },
            });
        "
    >
        <p class="text-sm font-semibold mb-2 text-slate-800">Grafik Jawaban per Kolom</p>
        <canvas x-ref="kraepelinCanvas" class="w-full h-64"></canvas>
    </div>
</div>

{{-- Script Load Chart.js --}}
@script
    (function () {
        function ensureChartJs(callback) {
            if (window.Chart) { callback(); return; }
            if (window.__kraepelinChartLoading) {
                document.addEventListener('kraepelinChartReady', callback, { once: true });
                return;
            }
            window.__kraepelinChartLoading = true;
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            s.onload = function () {
                document.dispatchEvent(new Event('kraepelinChartReady'));
                callback();
            };
            document.head.appendChild(s);
        }
        ensureChartJs(() => {});
    })();
@endscript