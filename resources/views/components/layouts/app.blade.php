<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Psikotes Karyawan' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Font: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">
    <div class="min-h-screen flex relative">

        {{-- MOBILE HEADER (Hanya muncul di HP/Tablet kecil) --}}
        <div class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-slate-200 z-40 flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-orange-500 text-white flex items-center justify-center text-xs font-bold shadow-md shadow-orange-200">
                    HR
                </div>
                <span class="font-bold text-slate-800">Online Test</span>
            </div>
            {{-- Tombol Buka Sidebar --}}
            <button id="mobile-menu-btn" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        {{-- OVERLAY (Gelap saat sidebar terbuka di HP) --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden transition-opacity opacity-0 backdrop-blur-sm"></div>

        {{-- SIDEBAR --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col h-full shadow-xl lg:shadow-none">
            
            {{-- Header Sidebar --}}
            <div class="px-6 h-16 lg:h-auto py-4 lg:py-6 border-b border-slate-100 flex items-center gap-3 justify-between lg:justify-start">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center text-sm font-bold shadow-lg shadow-orange-200">
                        HR
                    </div>
                    <div>
                        <h1 class="text-base font-bold text-slate-800 leading-tight">
                            Online HR Test
                        </h1>
                        <p class="text-[10px] text-slate-400 font-medium truncate w-32">
                            {{ auth()->user()->name ?? 'Guest User' }}
                        </p>
                    </div>
                </div>
                {{-- Tombol Close Sidebar (Hanya di HP) --}}
                <button id="close-sidebar-btn" class="lg:hidden p-1 text-slate-400 hover:text-slate-600 rounded-md hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Menu Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                
                {{-- DASHBOARD --}}
                <a href="{{ route('psikotes.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                   {{ request()->routeIs('psikotes.dashboard') 
                        ? 'bg-orange-50 text-orange-600 font-semibold ring-1 ring-orange-100' 
                        : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('psikotes.dashboard') ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="text-sm">Dashboard</span>
                </a>

                {{-- GROUP LABEL --}}
                <div class="pt-6 pb-2 px-3">
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                        Daftar Tes
                    </p>
                </div>

                {{-- 01. DERET ANGKA --}}
                <button type="button" disabled class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 cursor-not-allowed border border-transparent hover:bg-slate-50 transition-all text-left group">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-[10px] font-mono font-bold text-slate-400 group-hover:bg-slate-200 transition-colors">01</span>
                    <div class="flex flex-col">
                        <span class="text-xs font-medium group-hover:text-slate-500">Deret Angka</span>
                    </div>
                    <span class="ml-auto text-[9px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-400">Soon</span>
                </button>

                {{-- 02. PADANAN KATA --}}
                <button type="button" disabled class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 cursor-not-allowed border border-transparent hover:bg-slate-50 transition-all text-left group">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-[10px] font-mono font-bold text-slate-400 group-hover:bg-slate-200 transition-colors">02</span>
                    <div class="flex flex-col">
                        <span class="text-xs font-medium group-hover:text-slate-500">Padanan Kata</span>
                    </div>
                    <span class="ml-auto text-[9px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-400">Soon</span>
                </button>

                {{-- 03. PAPI KOSTICK --}}
                <button type="button" disabled class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 cursor-not-allowed border border-transparent hover:bg-slate-50 transition-all text-left group">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-[10px] font-mono font-bold text-slate-400 group-hover:bg-slate-200 transition-colors">03</span>
                    <div class="flex flex-col">
                        <span class="text-xs font-medium group-hover:text-slate-500">PAPI Kostick</span>
                    </div>
                    <span class="ml-auto text-[9px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-400">Soon</span>
                </button>

                {{-- 04. TES KRAEPELIN --}}
                <a href="{{ route('kraepelin.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 mt-2 border border-transparent
                   {{ request()->routeIs('kraepelin.*') 
                        ? 'bg-purple-50 text-purple-700 font-semibold ring-1 ring-purple-100 shadow-sm' 
                        : 'text-slate-600 hover:bg-purple-50/50 hover:text-purple-600' }}">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg transition-colors
                        {{ request()->routeIs('kraepelin.*') ? 'bg-purple-600 text-white shadow-md shadow-purple-200' : 'bg-slate-100 text-slate-500 group-hover:bg-purple-100 group-hover:text-purple-600' }} 
                        text-[10px] font-mono font-bold">
                        04
                    </span>
                    <span class="text-sm">Tes Kraepelin</span>
                    @if(request()->routeIs('kraepelin.*'))
                        <span class="ml-auto flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                        </span>
                    @endif
                </a>
            </nav>

            {{-- Footer Sidebar: Mode Admin & Logout --}}
            <div class="p-4 border-t border-slate-100 space-y-2">
                {{-- Tombol Switch to Admin --}}
                <a href="{{ route('switch.to.admin') }}" 
                   class="flex items-center justify-center gap-2 w-full py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-800 transition-all">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Login sebagai Admin
                </a>

                {{-- Tombol Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2 rounded-lg border border-red-100 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-200 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        {{-- Padding top hanya di HP agar tidak tertutup header mobile --}}
        <main class="flex-1 lg:pl-64 flex flex-col min-h-screen transition-all duration-300 pt-16 lg:pt-0">
            <div class="px-4 py-8 sm:px-8">
                {{ $slot }}
            </div>
        </main>
        
    </div>

    {{-- Script untuk Toggle Sidebar Mobile --}}
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            // Sedikit delay agar transisi opacity berjalan
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
            }, 10);
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            // Tunggu transisi selesai baru hide elemen
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
    </script>
</body>
</html>