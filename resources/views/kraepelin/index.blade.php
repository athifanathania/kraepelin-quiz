<x-layouts.app title="Riwayat Tes Kraepelin">
    <div class="max-w-4xl mx-auto space-y-6">
        
        {{-- HEADER SECTION --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-200/60 relative overflow-hidden">
            {{-- Decoration Background --}}
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-orange-100 to-transparent rounded-full opacity-50 blur-xl"></div>
            
            <div class="relative flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex gap-4">
                    {{-- Icon Header --}}
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-orange-50 rounded-2xl flex items-center justify-center flex-shrink-0 border border-orange-100 shadow-sm">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                            Tes Kraepelin
                        </h1>
                        <p class="text-slate-500 text-sm sm:text-base mt-1 leading-relaxed max-w-xl">
                            Uji ketahanan, kecepatan, dan ketelitian kerja melalui simulasi hitungan angka vertikal.
                        </p>
                    </div>
                </div>

                {{-- Badge Mode --}}
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 border border-slate-200 text-xs font-medium text-slate-500">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                        Mode: Individu
                    </span>
                </div>
            </div>
        </div>

        @php
            // Logic Check
            $latest    = $sessions->sortByDesc('started_at')->first();
            $canRetake = $latest && $latest->can_retake;
        @endphp

        {{-- CONTENT AREA --}}
        <div class="transition-all duration-500 ease-in-out">
            
            {{-- 1. BELUM PERNAH TES (Intro State) --}}
            @if ($sessions->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                    <div class="p-8 sm:p-12 text-center">
                        <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-indigo-50 text-indigo-500 mb-6 shadow-sm ring-4 ring-indigo-50/50">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        
                        <h2 class="text-lg font-semibold text-slate-900 mb-2">
                            Siap untuk memulai?
                        </h2>
                        <p class="text-slate-500 max-w-md mx-auto mb-6 text-sm leading-relaxed">
                            Pastikan Anda berada di tempat yang tenang. Tes ini membutuhkan fokus penuh selama kurang lebih 15-20 menit.
                        </p>

                        {{-- >>>>>> TAMBAHAN INSTRUKSI DISINI <<<<<< --}}
                        <div class="max-w-xl mx-auto bg-slate-50 border border-slate-100 rounded-xl p-5 mb-8 text-left">
                            <h3 class="flex items-center gap-2 font-semibold text-slate-800 mb-3 text-sm uppercase tracking-wide">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Instruksi Singkat
                            </h3>
                            <ul class="space-y-2.5 text-sm text-slate-600 leading-relaxed">
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-orange-400 flex-shrink-0"></span>
                                    <span>Jumlahkan dua angka yang berdekatan.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-orange-400 flex-shrink-0"></span>
                                    <span>Ketik <strong class="text-slate-800 font-semibold">hanya digit terakhir</strong> (satuan). <br><span class="text-slate-400 text-xs">Contoh: 8 + 6 = 14, ketik 4.</span></span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-orange-400 flex-shrink-0"></span>
                                    <span>Tekan angka untuk mengisi, kursor akan <strong>otomatis naik</strong>.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-orange-400 flex-shrink-0"></span>
                                    <span>Gunakan <code class="bg-white border border-slate-200 px-1 py-0.5 rounded text-xs font-mono text-slate-500">Backspace</code> untuk koreksi (turun ke bawah).</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-orange-400 flex-shrink-0"></span>
                                    <span>Jika waktu habis, kolom akan terkunci dan pindah otomatis ke sebelah kanan.</span>
                                </li>
                            </ul>
                        </div>
                        {{-- >>>>>> END TAMBAHAN INSTRUKSI <<<<<< --}}

                        <div class="flex flex-col items-center gap-4">
                            <a href="{{ route('kraepelin.start') }}" 
                               class="group relative inline-flex items-center justify-center px-8 py-3.5 text-sm font-semibold text-white transition-all duration-200 bg-orange-600 rounded-xl hover:bg-orange-700 hover:shadow-lg hover:shadow-orange-500/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-600 active:scale-95">
                                <span>Mulai Tes Sekarang</span>
                                <svg class="w-4 h-4 ml-2 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                            <p class="text-xs text-slate-400">
                                Sistem akan otomatis mencatat waktu mulai setelah tombol ditekan
                            </p>
                        </div>

                        {{-- Info Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-10 pt-8 border-t border-slate-100 text-left">
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50/50">
                                <div class="p-2 bg-white rounded-lg shadow-sm text-blue-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="text-xs">
                                    <span class="block font-semibold text-slate-700">Durasi</span>
                                    <span class="text-slate-500">15-20 Menit</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50/50">
                                <div class="p-2 bg-white rounded-lg shadow-sm text-purple-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="text-xs">
                                    <span class="block font-semibold text-slate-700">Penilaian</span>
                                    <span class="text-slate-500">Otomatis Realtime</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50/50">
                                <div class="p-2 bg-white rounded-lg shadow-sm text-emerald-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div class="text-xs">
                                    <span class="block font-semibold text-slate-700">Aspek</span>
                                    <span class="text-slate-500">Kecepatan & Ketelitian</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- 2. SUDAH PERNAH TES & BOLEH ULANG (Retake State) --}}
            @elseif ($canRetake)
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-amber-200 shadow-[0_4px_20px_-4px_rgba(251,191,36,0.15)] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-full opacity-50"></div>
                    
                    <div class="flex flex-col sm:flex-row gap-6 relative z-10">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center ring-4 ring-amber-50">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-slate-800">Izin Mengulang Tes Diberikan</h3>
                            <p class="text-slate-600 text-sm mt-1 mb-4">
                                HRD telah membuka akses bagi Anda untuk mengerjakan ulang tes ini. Harap manfaatkan kesempatan ini sebaik mungkin.
                            </p>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                <div class="text-xs text-amber-800">
                                    <span class="block font-medium mb-0.5">Tes Terakhir:</span>
                                    {{ optional($latest->started_at)->translatedFormat('l, d F Y - H:i') }}
                                </div>
                                <a href="{{ route('kraepelin.start') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                    Mulai Ulang Tes
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- 3. SUDAH SELESAI (Completed State) --}}
            @else
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-emerald-100 shadow-[0_4px_20px_-4px_rgba(16,185,129,0.1)]">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 ring-4 ring-emerald-50/50">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div class="flex-1 w-full">
                            <h3 class="text-lg font-bold text-slate-800">Tes Selesai Dikerjakan</h3>
                            <p class="text-slate-500 text-sm mt-1">
                                Terima kasih telah menyelesaikan Tes Kraepelin. Data hasil tes Anda telah tersimpan otomatis ke dalam sistem HR.
                            </p>
                            
                            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                <div class="flex-1 p-3 bg-slate-50 rounded-xl border border-slate-100 text-left">
                                    <span class="text-xs text-slate-400 font-medium uppercase tracking-wider">Tanggal Pengerjaan</span>
                                    <div class="font-semibold text-slate-700 mt-0.5">
                                        {{ optional($latest->started_at)->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                                <div class="flex-1 p-3 bg-slate-50 rounded-xl border border-slate-100 text-left">
                                    <span class="text-xs text-slate-400 font-medium uppercase tracking-wider">Waktu Mulai</span>
                                    <div class="font-semibold text-slate-700 mt-0.5">
                                        {{ optional($latest->started_at)->format('H:i') }} WIB
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-3 rounded-lg bg-blue-50 text-blue-700 text-xs flex gap-2 items-start">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Jika Anda merasa ada kendala teknis saat pengerjaan atau perlu melakukan tes ulang, silakan hubungi Administrator HR.</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-layouts.app>