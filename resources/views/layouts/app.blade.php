{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Kraepelin Test' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white border-r shadow-sm">
        <div class="px-4 py-4 border-b">
            <h1 class="text-lg font-bold">
                Kraepelin <span class="text-orange-500">Test</span>
            </h1>
            <p class="text-xs text-gray-500">
                {{ auth()->user()->name ?? '' }}
            </p>
        </div>

        <nav class="px-2 py-4 space-y-1 text-sm">
            {{-- Beranda / Riwayat --}}
            <a href="{{ route('kraepelin.index') }}"
               class="flex items-center px-3 py-2 rounded-md
                      {{ request()->routeIs('kraepelin.*') ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                Riwayat Tes Kraepelin
            </a>

            {{-- MENU QUIZ LAIN – nanti tinggal tambah route --}}
            <a href="#"
               class="flex items-center px-3 py-2 rounded-md text-gray-400 cursor-not-allowed">
                Tes DISC (coming soon)
            </a>
            <a href="#"
               class="flex items-center px-3 py-2 rounded-md text-gray-400 cursor-not-allowed">
                Tes Big Five (coming soon)
            </a>
        </nav>
    </aside>

    {{-- KONTEN --}}
    <main class="flex-1">
        <div class="px-6 py-6">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>
