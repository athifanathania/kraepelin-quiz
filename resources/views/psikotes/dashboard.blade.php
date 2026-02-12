<x-layouts.app title="Dashboard Psikotes">
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white/95 rounded-2xl shadow-[0_18px_45px_rgba(15,23,42,0.06)] border border-slate-100 p-6 sm:p-8 relative overflow-hidden">
            
            {{-- Background Decoration (Abstract) --}}
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-purple-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
            
            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 text-[11px] font-medium text-purple-700 mb-3 border border-purple-100">
                        <span>✨</span>
                        <span>Halo, {{ auth()->user()->name ?? 'Karyawan' }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
                        Selamat datang di <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-fuchsia-600">Recruitment Tes PT. Indomatsumoto P&D</span>
                    </h1>

                    <p class="text-slate-600 mt-2 text-sm sm:text-[15px] leading-relaxed max-w-2xl">
                        Platform asesmen psikologi terintegrasi. Silakan pilih modul tes yang tersedia di bawah ini untuk memulai proses evaluasi diri dan pengembangan karirmu.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2 text-[11px] text-slate-500">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm">
                            <span class="text-purple-500">🧠</span> <span>Fokus & Konsentrasi</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm">
                            <span class="text-fuchsia-500">👥</span> <span>Kepribadian</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm">
                            <span class="text-blue-500">⚡</span> <span>Kecepatan Kerja</span>
                        </span>
                    </div>
                </div>

                {{-- KARTU KECIL STATUS / INFO --}}
                <div class="w-full sm:w-auto mt-4 sm:mt-0 z-10">
                    <div class="bg-slate-900 text-slate-50 rounded-2xl p-5 sm:min-w-[240px] shadow-xl shadow-slate-200 border border-slate-800 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-purple-500 blur-[50px] opacity-20 group-hover:opacity-30 transition duration-500"></div>

                        <div class="relative">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 mb-1 font-semibold">
                                Akun Peserta
                            </p>
                            <p class="text-sm font-medium truncate max-w-[200px]" title="{{ auth()->user()->email }}">
                                {{ auth()->user()->email ?? 'User terdaftar' }}
                            </p>
                            
                            <div class="h-px w-full bg-slate-800 my-3"></div>

                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-slate-400">Status</span>
                                    <span class="text-xs text-emerald-400 font-medium flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Active
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 rounded-md bg-purple-500/10 text-purple-300 border border-purple-500/20 text-[10px] font-medium">
                                        HR Test
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KONTEN TES --}}
        <div class="bg-white/95 rounded-2xl shadow-[0_14px_35px_rgba(15,23,42,0.05)] border border-slate-100 p-6 sm:p-7">
            <div class="flex items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-1 h-6 bg-purple-600 rounded-full"></span>
                        Daftar Tes Psikologi
                    </h2>
                    <p class="text-xs text-slate-500 mt-1 ml-3">
                        Pilih tes yang diinstruksikan oleh tim HRD.
                    </p>
                </div>
                <div class="hidden sm:flex items-center gap-2 text-[11px] bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                    </span>
                    <span class="text-slate-600 font-medium">Sistem Online</span>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                {{-- 01. TES DERET ANGKA (COMING SOON) --}}
                <div class="border border-slate-100 rounded-2xl p-5 bg-slate-50/50 flex flex-col justify-between opacity-75 hover:opacity-100 transition duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-200/50 text-[10px] font-semibold text-slate-500">
                                ⏳ DEVELOPMENT
                            </div>
                            <span class="text-[10px] font-mono text-slate-300">01</span>
                        </div>

                        <h3 class="font-semibold text-lg text-slate-700 mb-1">
                            Tes Deret Angka
                        </h3>
                        <p class="text-xs text-slate-500 mb-3 leading-relaxed">
                            Mengukur kemampuan logika matematika dan analisa pola angka untuk pemecahan masalah.
                        </p>
                    </div>

                    <div class="flex items-center justify-between mt-2 pt-3 border-t border-slate-100">
                        <span class="text-[10px] font-medium text-slate-400 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            Coming soon
                        </span>
                        <span class="grayscale text-lg opacity-30">🔢</span>
                    </div>
                </div>

                {{-- 02. TES PADANAN KATA (COMING SOON) --}}
                <div class="border border-slate-100 rounded-2xl p-5 bg-slate-50/50 flex flex-col justify-between opacity-75 hover:opacity-100 transition duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-200/50 text-[10px] font-semibold text-slate-500">
                                ⏳ DEVELOPMENT
                            </div>
                            <span class="text-[10px] font-mono text-slate-300">02</span>
                        </div>

                        <h3 class="font-semibold text-lg text-slate-700 mb-1">
                            Tes Padanan Kata
                        </h3>
                        <p class="text-xs text-slate-500 mb-3 leading-relaxed">
                            Mengukur kemampuan verbal, logika bahasa, dan pemahaman analogi kata.
                        </p>
                    </div>

                    <div class="flex items-center justify-between mt-2 pt-3 border-t border-slate-100">
                        <span class="text-[10px] font-medium text-slate-400 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            Coming soon
                        </span>
                        <span class="grayscale text-lg opacity-30">📖</span>
                    </div>
                </div>

                {{-- 03. TES PAPI KOSTICK (COMING SOON) --}}
                <div class="border border-slate-100 rounded-2xl p-5 bg-slate-50/50 flex flex-col justify-between opacity-75 hover:opacity-100 transition duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-200/50 text-[10px] font-semibold text-slate-500">
                                ⏳ DEVELOPMENT
                            </div>
                            <span class="text-[10px] font-mono text-slate-300">03</span>
                        </div>

                        <h3 class="font-semibold text-lg text-slate-700 mb-1">
                            Tes PAPI Kostick
                        </h3>
                        <p class="text-xs text-slate-500 mb-3 leading-relaxed">
                            Personality inventory untuk mengevaluasi peran dan perilaku individu dalam situasi kerja.
                        </p>
                    </div>

                    <div class="flex items-center justify-between mt-2 pt-3 border-t border-slate-100">
                        <span class="text-[10px] font-medium text-slate-400 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            Coming soon
                        </span>
                        <span class="grayscale text-lg opacity-30">👔</span>
                    </div>
                </div>

                {{-- 04. TES KRAEPELIN (ACTIVE / HERO CARD) --}}
                <div class="group relative border border-purple-100 rounded-2xl p-5 bg-gradient-to-br from-purple-50 via-white to-white flex flex-col justify-between overflow-hidden hover:shadow-lg hover:shadow-purple-100/50 transition duration-300 ring-1 ring-purple-100">
                    {{-- Dekorasi Bubble Ungu --}}
                    <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-purple-100/50 group-hover:bg-purple-100/80 transition"></div>
                    <div class="pointer-events-none absolute -right-10 bottom-0 h-32 w-32 rounded-full bg-fuchsia-100/30 group-hover:bg-fuchsia-100/50 transition"></div>

                    <div class="relative">
                        <div class="flex justify-between items-start mb-3">
                            <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white border border-purple-100 text-[10px] font-bold text-purple-700 shadow-sm">
                                🚀 <span class="tracking-wide">READY</span>
                            </div>
                            <span class="text-[10px] font-mono text-purple-300 font-bold">04</span>
                        </div>

                        <h3 class="font-bold text-lg text-slate-900 mb-1 group-hover:text-purple-700 transition">
                            Tes Kraepelin
                        </h3>
                        <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                            Uji ketahanan, kecepatan, dan ketelitian kerja Anda melalui simulasi hitungan angka vertikal.
                        </p>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-[11px] text-slate-500">
                                <svg class="w-3.5 h-3.5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Durasi: ~15-20 Menit
                            </div>
                            <div class="flex items-center text-[11px] text-slate-500">
                                <svg class="w-3.5 h-3.5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Hasil Realtime
                            </div>
                        </div>
                    </div>

                    <div class="relative mt-2">
                        <a href="{{ route('kraepelin.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-purple-600 text-white text-xs sm:text-sm rounded-xl font-medium shadow-md shadow-purple-200 hover:bg-purple-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <span>Mulai Tes Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
</x-layouts.app>