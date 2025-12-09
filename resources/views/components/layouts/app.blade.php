{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Psikotes Karyawan' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Google Font: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    @vite('resources/css/app.css')

    <style>
        body {
            font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont,
                "Segoe UI", sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200">
<div class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white/95 border-r border-slate-200 shadow-[0_8px_30px_rgba(15,23,42,0.06)] flex flex-col">
        <div class="px-4 py-5 border-b border-slate-200">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-xl bg-orange-500 text-white flex items-center justify-center text-sm font-semibold">
                    Ps
                </div>
                <div>
                    <h1 class="text-base font-semibold tracking-tight text-slate-800">
                        Online <span class="text-orange-500">HR Test</span>
                    </h1>
                    <p class="text-[11px] text-slate-500">
                        {{ auth()->user()->name ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        <nav class="px-2 py-4 space-y-1 text-sm flex-1">
            {{-- DASHBOARD --}}
            <a href="{{ route('psikotes.dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                      {{ request()->routeIs('psikotes.dashboard')
                            ? 'bg-orange-50 text-orange-600 font-semibold'
                            : 'text-slate-700 hover:bg-slate-50' }}">
                <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-orange-100 text-[11px] text-orange-600">
                    ◎
                </span>
                <span>Dashboard Psikotes</span>
            </a>

            {{-- MENU QUIZ LAIN – coming soon --}}
            <div class="pt-3 mt-3 border-t border-dashed border-slate-200">
                <p class="px-3 mb-1 text-[11px] uppercase tracking-wide text-slate-400">
                    Online Test
                </p>
                <button type="button"
                   class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-slate-400 cursor-not-allowed bg-slate-50/60">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-slate-100 text-[11px]">
                        01
                    </span>
                    <span>Tes Deret Angka (coming soon)</span>
                </button>

                <button type="button"
                   class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-slate-400 cursor-not-allowed bg-slate-50/60">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-slate-100 text-[11px]">
                        02
                    </span>
                    <span>Tes Padanan Kata (coming soon)</span>
                </button>

                <button type="button"
                   class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-slate-400 cursor-not-allowed bg-slate-50/60">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-slate-100 text-[11px]">
                        03
                    </span>
                    <span>Tes PAPI Kostick (coming soon)</span>
                </button>

                <a href="{{ route('kraepelin.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                        {{ request()->routeIs('kraepelin.*')
                                ? 'bg-orange-50 text-orange-600 font-semibold'
                                : 'text-slate-700 hover:bg-slate-50' }}">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-slate-100 text-[11px] text-slate-600">
                        04
                    </span>
                    <span>Tes Kraepelin</span>
                </a>
            </div>
        </nav>

        {{-- TOMBOL LOGIN ADMIN --}}
        <div class="px-3 pb-4 pt-2 border-t border-slate-200">
            <a href="{{ url('/admin/login') }}"
               class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg border text-xs font-medium
                      border-orange-500 text-orange-600 hover:bg-orange-50 transition">
                <span class="h-4 w-4 rounded-full border border-orange-400 flex items-center justify-center text-[10px]">
                    ⚙
                </span>
                <span>Login sebagai Admin</span>
            </a>
        </div>
    </aside>

    {{-- KONTEN --}}
    <main class="flex-1">
        <div class="px-4 sm:px-8 py-6">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>
