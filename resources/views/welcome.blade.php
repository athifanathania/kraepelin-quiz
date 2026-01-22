<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HR Recruitment Portal - Indomatsumoto</title>

    {{-- Load CSS & JS via Vite --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Fallback Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-sans antialiased relative overflow-hidden selection:bg-teal-500 selection:text-white">

    {{-- Background Gradient: Hijau ke Biruan (Emerald to Cyan) --}}
    <div class="fixed inset-0 -z-10">
        {{-- Base Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-100"></div>
        
        {{-- Decorative Blobs --}}
        <div class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 w-[600px] h-[600px] rounded-full bg-teal-400/20 blur-[100px]"></div>
        <div class="absolute bottom-0 right-0 translate-x-1/4 translate-y-1/4 w-[600px] h-[600px] rounded-full bg-cyan-400/20 blur-[100px]"></div>
        
        {{-- Grid Pattern Overlay --}}
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#0f766e1a_1px,transparent_1px),linear-gradient(to_bottom,#0f766e1a_1px,transparent_1px)] bg-[size:32px_32px] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,#000_70%,transparent_100%)]"></div>
    </div>

    <main class="min-h-screen flex items-center justify-center p-6">
        
        {{-- Main Card --}}
        <div class="relative w-full max-w-md">
            
            {{-- Glassmorphism Card --}}
            <div class="relative overflow-hidden rounded-3xl bg-white/80 backdrop-blur-xl shadow-2xl ring-1 ring-teal-900/5 p-8 md:p-10 animate-float text-center">
                
                {{-- Logo / Icon Area --}}
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-400 to-cyan-600 shadow-lg text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                    </svg>
                </div>

                {{-- Headings --}}
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight mb-2">
                    HR Recruitment
                </h1>
                <p class="text-slate-500 font-medium text-sm mb-8 leading-relaxed">
                    PT. Indomatsumoto Press & Dies Industries.<br>
                    Platform Psikotes & Seleksi Online.
                </p>

                {{-- Action Buttons --}}
                <div class="space-y-4">
                    @auth
                        {{-- Jika User SUDAH Login --}}
                        <a href="{{ route('psikotes.dashboard') }}" 
                           class="block w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-bold shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 hover:scale-[1.02] transition-all duration-200">
                            Lanjutkan ke Dashboard
                        </a>
                        <div class="text-xs text-slate-400">
                            Anda sudah login sebagai <span class="font-semibold text-teal-600">{{ Auth::user()->name }}</span>
                        </div>
                    @else
                        {{-- Jika User BELUM Login --}}
                        <a href="{{ route('login') }}" 
                           class="group relative block w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-bold shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 hover:scale-[1.02] transition-all duration-200">
                            <span class="flex items-center justify-center gap-2">
                                Login Peserta
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 transition-transform group-hover:translate-x-1">
                                    <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </a>

                        <a href="{{ route('register') }}" 
                           class="block w-full py-3.5 px-6 rounded-xl bg-white border-2 border-slate-100 text-slate-600 font-bold hover:border-teal-200 hover:text-teal-600 hover:bg-teal-50 transition-all duration-200">
                            Daftar Akun Baru
                        </a>
                    @endauth
                </div>

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <p class="text-xs text-slate-400">
                        &copy; {{ date('Y') }} HR Department System.
                    </p>
                </div>

            </div>
        </div>
    </main>

</body>
</html>