<x-layouts.app title="Riwayat Tes Kraepelin">
    <div class="bg-white/95 backdrop-blur-sm p-6 sm:p-8 rounded-2xl shadow-[0_18px_45px_rgba(15,23,42,0.06)] border border-slate-100">
        <div class="flex items-start justify-between gap-3 mb-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
                    Tes Kraepelin
                </h1>
                <p class="text-slate-500 mt-1 text-sm sm:text-[15px]">
                    Kerjakan tes ini satu kali saja sesuai instruksi HR / psikolog perusahaan.
                </p>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400">
                <span class="inline-flex h-6 px-2 items-center rounded-full bg-slate-50 border border-slate-200">
                    Mode: Tes individu
                </span>
            </div>
        </div>

        @php
            // Ambil sesi terbaru (kalau ada)
            $latest    = $sessions->sortByDesc('started_at')->first();
            $canRetake = $latest && $latest->can_retake;
        @endphp

        {{-- 1) BELUM PERNAH TES -> boleh mulai pertama kali --}}
        @if ($sessions->isEmpty())
            <div class="border border-dashed border-slate-200 rounded-xl p-6 text-center text-slate-500 text-sm mb-4">
                Kamu belum pernah mengerjakan Tes Kraepelin.
                <br>
                Silakan klik tombol di bawah untuk memulai tes pertama kamu.
            </div>

            <div class="flex justify-center">
                <a href="{{ route('kraepelin.start') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-orange-500 text-white rounded-xl text-sm font-semibold shadow-sm hover:bg-orange-600 transition">
                    <span class="text-base leading-none">+</span>
                    Mulai tes sekarang
                </a>
            </div>

        {{-- 2) SUDAH PERNAH TES & DIIZINKAN ULANG -> tampil info + tombol MULAI ULANG --}}
        @elseif ($canRetake)
            <div class="rounded-2xl border border-amber-100 bg-amber-50/80 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div class="flex items-start gap-3">
                    <div class="mt-1 flex h-8 w-8 items-center justify-center rounded-full bg-amber-500 text-white text-sm font-semibold">
                        ↻
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-amber-900">
                            HR / admin telah mengizinkan kamu untuk mengulang Tes Kraepelin.
                        </p>
                        <p class="text-xs text-amber-800 mt-1">
                            Tes sebelumnya dikerjakan pada:
                            <span class="font-medium">
                                {{ optional($latest->started_at)->format('d-m-Y H:i:s') }}
                            </span>
                        </p>
                        <p class="text-xs text-amber-800">
                            Silakan klik tombol <span class="font-semibold">Mulai ulang tes</span> untuk memulai sesi baru.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('kraepelin.start') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-500 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm hover:bg-orange-600 transition">
                        Mulai ulang tes
                    </a>
                </div>
            </div>

        {{-- 3) SUDAH PERNAH TES & TIDAK DIIZINKAN ULANG -> hanya info, tanpa tombol --}}
        @else
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="mt-1 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-white text-sm font-semibold">
                        ✓
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-emerald-900">
                            Kamu sudah menyelesaikan Tes Kraepelin
                        </p>
                        <p class="text-xs text-emerald-800 mt-1">
                            Tanggal tes:
                            <span class="font-medium">
                                {{ optional($latest->started_at)->format('d-m-Y H:i:s') }}
                            </span>
                        </p>
                        <p class="text-xs text-emerald-800">
                            Untuk melihat atau menilai hasil tes, atau jika diperlukan pengulangan tes,
                            silakan hubungi HR / admin yang bertanggung jawab.
                        </p>
                    </div>
                </div>

                <div class="text-[11px] text-emerald-900 bg-white/70 border border-emerald-100 rounded-full px-3 py-1">
                    Tes hanya bisa dikerjakan satu kali oleh peserta. Pengulangan tes hanya dapat
                    dilakukan atas persetujuan HR / admin.
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
