<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Online HR Test</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-900">

<div class="min-h-screen bg-gradient-to-br from-indigo-200 via-indigo-50 to-sky-200 relative overflow-hidden flex items-center justify-center py-10">

    <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-400 rounded-full blur-3xl opacity-40"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-sky-400 rounded-full blur-3xl opacity-40"></div>

    <div class="relative z-10 w-full max-w-md px-6">

        <div class="text-center mb-6">
            <div class="mx-auto h-14 w-14 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>

            <h1 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-sky-500">
                Buat Akun Baru
            </h1>
            <p class="mt-2 text-sm text-slate-600">
                Bergabung untuk mengikuti tes online
            </p>
        </div>

        <div class="bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl shadow-indigo-300/50 border border-white">

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="inline-flex items-center px-3 py-1 mb-1 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-full">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input name="name" type="text" value="{{ old('name') }}" required autofocus
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                            placeholder="Contoh: Budi Santoso">
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center px-3 py-1 mb-1 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-full">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <input name="email" type="email" value="{{ old('email') }}" required
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center px-3 py-1 mb-1 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-full">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <input name="password" type="password" required
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                            placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center px-3 py-1 mb-1 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-full">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input name="password_confirmation" type="password" required
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-2 py-3 rounded-xl font-bold text-white bg-gradient-to-r from-indigo-600 to-sky-500 hover:from-indigo-700 hover:to-sky-600 shadow-lg shadow-indigo-300/40 transition transform active:scale-95">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500 mb-2">Sudah punya akun?</p>
                <a href="{{ route('login') }}"
                    class="inline-block w-full py-3 rounded-xl border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition">
                    Masuk ke Akun
                </a>
            </div>

        </div>

        <p class="text-center text-xs text-slate-500 mt-8 mb-4">
            © {{ date('Y') }} Indomatsumoto HR Test Platform. Secure Registration.
        </p>

    </div>
</div>

</body>
</html>