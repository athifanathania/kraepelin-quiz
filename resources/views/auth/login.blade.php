<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Online HR Test</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-900">

<!-- Background -->
<div class="min-h-screen bg-gradient-to-br from-indigo-200 via-indigo-50 to-sky-200 relative overflow-hidden flex items-center justify-center">

    <!-- Blur blobs -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-400 rounded-full blur-3xl opacity-40"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-sky-400 rounded-full blur-3xl opacity-40"></div>

    <div class="relative z-10 w-full max-w-md px-6">

        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>

            <h1 class="text-4xl font-black bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-sky-500">
                Online HR Test
            </h1>
            <p class="mt-2 text-sm text-slate-600">
                Silakan masuk untuk memulai ujian
            </p>
        </div>

        <!-- Card -->
        <div class="bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl shadow-indigo-300/50 border border-white">

            {{-- STATUS --}}
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-xl text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
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
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            placeholder="masukkan email anda">
                    </div>
                </div>

                <!-- Password -->
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
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="remember" class="rounded text-indigo-600">
                        <span>Ingat saya</span>
                    </label>

                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">
                        Lupa password?
                    </a>
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full py-3 rounded-xl font-bold text-white bg-gradient-to-r from-indigo-600 to-sky-500 hover:from-indigo-700 hover:to-sky-600 shadow-lg shadow-indigo-300/40 transition">
                    Masuk
                </button>
            </form>

            <!-- Register -->
            @if (Route::has('register'))
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500 mb-2">Peserta baru?</p>
                <a href="{{ route('register') }}"
                    class="inline-block w-full py-3 rounded-xl border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold">
                    Daftar Akun Baru
                </a>
            </div>
            @endif

        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-500 mt-8">
            © {{ date('Y') }} Indomatsumoto HR Test Platform. All rights reserved.
        </p>

    </div>
</div>

</body>
</html>
