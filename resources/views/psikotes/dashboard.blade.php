<x-layouts.app title="Dashboard Psikotes">
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white/95 rounded-2xl shadow-[0_18px_45px_rgba(15,23,42,0.06)] border border-slate-100 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-[11px] font-medium text-orange-700 mb-3">
                        <span>✨</span>
                        <span>Halo, {{ auth()->user()->name ?? 'Karyawan' }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
                        Selamat datang di <span class="text-orange-500">Online HR Test</span>
                    </h1>

                    <p class="text-slate-600 mt-2 text-sm sm:text-[15px] leading-relaxed">
                        Di sini kamu bisa mengerjakan berbagai tes psikologi yang digunakan dalam proses
                        seleksi dan pengembangan karyawan. Silakan pilih tes yang ingin kamu kerjakan.
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2 text-[11px] text-slate-500">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50 border border-slate-200">
                            🧠 <span>Fokus & konsentrasi kerja</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50 border border-slate-200">
                            👥 <span>Kepribadian & gaya kerja</span>
                        </span>
                    </div>
                </div>

                {{-- KARTU KECIL STATUS / INFO --}}
                <div class="w-full sm:w-auto">
                    <div class="bg-slate-900 text-slate-50 rounded-2xl px-5 py-4 sm:min-w-[210px]">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400 mb-1">
                            Status akun
                        </p>
                        <p class="text-sm font-semibold">
                            {{ auth()->user()->email ?? 'User terdaftar' }}
                        </p>
                        <p class="text-[12px] text-slate-300 mt-1">
                            Data hasil tes akan direkap dan dapat dilihat kembali kapan saja.
                        </p>
                        <div class="mt-3 flex items-center justify-between text-[11px] text-slate-300">
                            <span class="inline-flex items-center gap-1">
                                ✅ <span>Terautentikasi</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/40">
                                HR use only
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KONTEN TES --}}
        <div class="bg-white/95 rounded-2xl shadow-[0_14px_35px_rgba(15,23,42,0.05)] border border-slate-100 p-6 sm:p-7">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">
                        Daftar Tes Psikologi
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Pilih salah satu tes di bawah ini. Beberapa tes masih dalam tahap pengembangan.
                    </p>
                </div>
                <div class="hidden sm:flex items-center gap-2 text-[11px] text-slate-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    <span>Sistem siap digunakan</span>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                {{-- CARD TES KRAEPELIN --}}
                <div class="relative border border-orange-100 rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-orange-50 via-amber-50 to-white flex flex-col justify-between overflow-hidden">
                    {{-- bubble lucu --}}
                    <div class="pointer-events-none absolute -right-6 -top-6 h-20 w-20 rounded-full bg-orange-100/60"></div>
                    <div class="pointer-events-none absolute -right-12 bottom-0 h-28 w-28 rounded-full bg-amber-100/50"></div>

                    <div class="relative">
                        <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-white/70 border border-orange-100 text-[11px] text-orange-700 mb-2">
                            🔢 <span>Aktif</span>
                        </div>

                        <h3 class="font-semibold text-lg text-slate-900 mb-1">
                            Tes Kraepelin
                        </h3>
                        <p class="text-xs text-slate-600 mb-2">
                            Mengukur konsentrasi, kecepatan, dan ketelitian kerja melalui penjumlahan deret angka vertikal.
                        </p>

                        <ul class="text-[11px] text-slate-500 space-y-1 mb-3">
                            <li>• Waktu terbatas per kolom</li>
                            <li>• Hasil otomatis direkap per sesi</li>
                            <li>• Cocok untuk posisi yang membutuhkan ketelitian tinggi</li>
                        </ul>
                    </div>

                    <div class="relative flex items-center justify-between gap-2 mt-1">
                        <a href="{{ route('kraepelin.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-500 text-white text-xs sm:text-sm rounded-lg font-medium shadow-sm hover:bg-orange-600 transition">
                            <span>Riwayat & mulai tes</span>
                            <span class="text-[13px]">→</span>
                        </a>
                        <p class="text-[11px] text-orange-700/90">
                            Disarankan dikerjakan di tempat yang tenang.
                        </p>
                    </div>
                </div>

                {{-- CARD TES DISC (COMING SOON) --}}
                <div class="border border-slate-200 rounded-2xl p-4 sm:p-5 bg-slate-50/80 flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-100 text-[11px] text-slate-500 mb-2">
                            👥 <span>Kepribadian kerja</span>
                        </div>

                        <h3 class="font-semibold text-lg text-slate-800 mb-1">
                            Tes DISC
                        </h3>
                        <p class="text-xs text-slate-600 mb-3">
                            Mengukur gaya kepribadian (Dominance, Influence, Steadiness, Compliance)
                            dan cara seseorang berinteraksi di lingkungan kerja.
                        </p>
                    </div>

                    <div class="flex items-center justify-between mt-1">
                        <span class="inline-flex items-center gap-2 text-[11px] px-3 py-1.5 rounded-full bg-slate-100 text-slate-500">
                            <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                            Coming soon
                        </span>
                        <span class="text-[11px] text-slate-400">
                            Sedang disiapkan oleh tim HR 🧩
                        </span>
                    </div>
                </div>

                {{-- CARD TES BIG FIVE (COMING SOON) --}}
                <div class="border border-slate-200 rounded-2xl p-4 sm:p-5 bg-slate-50/80 flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-100 text-[11px] text-slate-500 mb-2">
                            🌈 <span>5 dimensi kepribadian</span>
                        </div>

                        <h3 class="font-semibold text-lg text-slate-800 mb-1">
                            Tes Big Five
                        </h3>
                        <p class="text-xs text-slate-600 mb-3">
                            Menilai kepribadian berdasarkan lima dimensi utama:
                            Openness, Conscientiousness, Extraversion, Agreeableness, dan Neuroticism.
                        </p>
                    </div>

                    <div class="flex items-center justify-between mt-1">
                        <span class="inline-flex items-center gap-2 text-[11px] px-3 py-1.5 rounded-full bg-slate-100 text-slate-500">
                            <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                            Coming soon
                        </span>
                        <span class="text-[11px] text-slate-400">
                            Untuk mapping potensi karyawan 💼
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
