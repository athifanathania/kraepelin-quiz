@php 
    /** @var \App\Models\TestSession $record */
    $labels   = $perColumn->pluck('column_index')->map(fn ($i) => 'Kolom '.$i);
    $correct  = $perColumn->pluck('correct');

    // Totalkan dari seluruh kolom (REAL yang dikerjakan pengguna)
    $totalAnswered = $perColumn->sum('answered');
    $totalCorrect  = $perColumn->sum('correct');
    $totalWrong    = $perColumn->sum('wrong');

    // --- TOTAL KOTAK IDEAL YANG SEHARUSNYA DIISI ---
    // Asumsi: 27 kotak per kolom, dan perColumn berisi 50 kolom
    $boxesPerColumn = 27;                           // <- ubah kalau layoutmu beda
    $totalColumns   = $perColumn->count();          // misal 50 kolom
    $totalTarget    = $boxesPerColumn * $totalColumns;

    // Akurasi berdasarkan TOTAL KOTAK IDEAL
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
        {{-- Baris judul + chip akurasi --}}
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="kraepelin-header-title">
                    Hasil Tes Kraepelin
                </p>
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
                    <span class="font-semibold">
                        {{ $computedAccuracy !== null ? $computedAccuracy.'%' : '—' }}
                    </span>
                </span>
                <span class="text-[10px] text-slate-600">
                    Berdasarkan {{ $totalTarget }} kotak yang seharusnya diisi
                </span>
            </div>
        </div>

        {{-- Isi utama: info peserta/tes + statistik angka --}}
        <div class="kraepelin-header-main mt-4">
            {{-- Kiri: Peserta & Tes --}}
            <div class="space-y-3">
                <div class="rounded-xl bg-white/80 px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase">
                        Peserta
                    </p>
                    <p class="mt-0.5 text-[13px] font-semibold text-slate-900">
                        {{ $record->user?->name ?? '—' }}
                    </p>
                    <p class="text-[11px] text-slate-500">
                        {{ $record->user?->email ?? '—' }}
                    </p>
                </div>

                <div class="rounded-xl bg-white/80 px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase">
                        Tes
                    </p>
                    <p class="mt-0.5 text-[13px] font-semibold text-slate-900">
                        {{ $record->test?->name }} ({{ $record->test?->code }})
                    </p>
                    <p class="text-[11px] text-slate-500">
                        Mulai: {{ optional($record->started_at)->format('d-m-Y H:i:s') ?? '—' }}
                    </p>
                </div>
            </div>

            {{-- Kanan: 3 kartu statistik --}}
            <div class="kraepelin-stat-grid text-[11px]">

                {{-- HARUS DIISI --}}
                <div class="rounded-xl border border-slate-200 bg-white px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase">
                        Harus diisi
                    </p>
                    <p class="mt-0.5 text-lg font-semibold text-slate-900 leading-none">
                        {{ $totalTarget }}
                    </p>
                    <p class="mt-0.5 text-[10px] text-slate-500">
                        Total kotak seharusnya dikerjakan
                    </p>
                </div>

                {{-- DIISI (REAL) --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase">
                        Diisi
                    </p>
                    <p class="mt-0.5 text-lg font-semibold text-slate-900 leading-none">
                        {{ $totalAnswered }}
                    </p>
                    <p class="mt-0.5 text-[10px] text-slate-500">
                        Kotak yang benar-benar terjawab
                    </p>
                </div>

                {{-- BENAR --}}
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold text-emerald-700 uppercase">
                        Benar
                    </p>
                    <p class="mt-0.5 text-lg font-semibold text-emerald-700 leading-none">
                        {{ $totalCorrect }}
                    </p>
                    <p class="mt-0.5 text-[10px] text-emerald-700/80">
                        Dari {{ $totalTarget }} kotak
                    </p>
                </div>

                {{-- kartu SALAH tetap pakai yang tadi di bawah tabel, nggak perlu diubah --}}
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

            if (ctx.__kraepelinChartInstance) {
                ctx.__kraepelinChartInstance.destroy();
            }

            ctx.__kraepelinChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Benar per kolom',
                            data: correct,
                            tension: 0.3,
                            borderWidth: 2,
                            pointRadius: 3,
                            borderColor: 'rgba(59, 130, 246, 1)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: { display: true, text: 'Kolom' },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            title: { display: true, text: 'Jumlah jawaban benar' },
                        },
                    },
                    plugins: {
                        legend: { display: false },
                    },
                },
            });
        "
    >
        <p class="text-sm font-semibold mb-2 text-slate-800">
            Grafik Jawaban per Kolom
        </p>
        <canvas x-ref="kraepelinCanvas" class="w-full h-64"></canvas>
    </div>

    {{-- ================= TABEL DETAIL ================= --}}
    <div class="p-4 rounded-xl bg-white border">
        <p class="text-sm font-semibold mb-3 text-slate-800">
            Detail per Kolom
        </p>

        @php
            $chunks = $perColumn->chunk(ceil($perColumn->count() / 2));
        @endphp

        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($chunks as $chunk)
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="max-h-72 overflow-y-auto">
                        <table class="min-w-full text-[9px] leading-tight border-collapse">
                            <thead class="bg-slate-100 sticky top-0 z-10">
                                <tr>
                                    <th class="px-2 py-1 border border-slate-200 text-left font-semibold">
                                        Kolom
                                    </th>
                                    <th class="px-2 py-1 border border-slate-200 text-right font-semibold">
                                        Diisi
                                    </th>
                                    <th class="px-2 py-1 border border-slate-200 text-right font-semibold text-emerald-700">
                                        Benar
                                    </th>
                                    <th class="px-2 py-1 border border-slate-200 text-right font-semibold text-rose-700">
                                        Salah
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($chunk as $col)
                                    <tr class="{{ $loop->odd ? 'bg-white' : 'bg-slate-50' }}">
                                        <td class="px-2 py-1 border border-slate-200">
                                            Kolom {{ $col->column_index }}
                                        </td>

                                        <td class="px-2 py-1 border border-slate-200 text-right text-slate-700">
                                            {{ $col->answered }}
                                        </td>

                                        <td class="px-2 py-1 border border-slate-200 text-right">
                                            <span class="font-semibold" style="color:#16a34a;">
                                                {{ $col->correct }}
                                            </span>
                                        </td>

                                        <td class="px-2 py-1 border border-slate-200 text-right">
                                            <span class="font-semibold" style="color:#dc2626;">
                                                {{ $col->wrong }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@script
    (function () {
        function ensureChartJs(callback) {
            if (window.Chart) {
                callback();
                return;
            }

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
            s.onerror = function () {
                console.error('Gagal memuat Chart.js dari CDN');
            };
            document.head.appendChild(s);
        }

        ensureChartJs(() => {
            // Chart dirender dari x-init di atas
        });
    })();
@endscript
