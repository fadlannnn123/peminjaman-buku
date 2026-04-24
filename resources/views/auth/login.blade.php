<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Peminjaman Buku</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-900 min-h-screen flex items-center justify-center p-4">

    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-2xl shadow-indigo-500/25 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Sistem Perpustakaan</h1>
            <p class="text-slate-400 mt-1">Masuk ke akun Anda</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-8 shadow-2xl">
            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/30 text-red-200 px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-slate-300 mb-2">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="Masukkan username">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="Masukkan password">
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember"
                        class="w-4 h-4 text-indigo-600 bg-white/10 border-white/20 rounded focus:ring-indigo-500">
                    <label for="remember" class="ml-2 text-sm text-slate-300">Ingat saya</label>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-transparent transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-slate-400 text-sm">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">Daftar di sini</a>
                </p>
            </div>
        </div>

        <!-- Role Info -->
        <div class="mt-6 grid grid-cols-2 gap-4">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg mx-auto mb-2 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-white">Admin</p>
                <p class="text-xs text-slate-400">Kelola sistem</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-blue-500/20 rounded-lg mx-auto mb-2 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-white">Siswa</p>
                <p class="text-xs text-slate-400">Pinjam buku</p>
            </div>
        </div>
    </div>
</body>
</html>
