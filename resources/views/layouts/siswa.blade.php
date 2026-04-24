<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Peminjaman Buku') }} - Siswa</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        :root {
            /* DEFAULT DARK THEME */
            --bg-body: #0f172a;
            --bg-card: rgba(30,41,59,0.7);
            --border-card: rgba(255,255,255,0.1);
            --bg-glass: rgba(15,23,42,0.9);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        [data-theme="light"] {
            /* LIGHT THEME */
            --bg-body: #f1f5f9;
            --bg-card: #ffffff;
            --border-card: #cbd5e1;
            --bg-glass: rgba(241,245,249,0.9);
            --text-main: #1e293b;
            --text-muted: #64748b;
        }
        
        body { background: var(--bg-body); color: var(--text-main); transition: background-color 0.3s ease, color 0.3s ease; }
        .card-bg { background: var(--bg-card); border: 1px solid var(--border-card); transition: background-color 0.3s ease, border-color 0.3s ease; }
        .glass-header { background: var(--bg-glass); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-card); transition: background-color 0.3s ease, border-color 0.3s ease; }

        /* OVERRIDES ONLY IN LIGHT MODE */
        [data-theme="light"] h1, [data-theme="light"] h2, [data-theme="light"] h3, [data-theme="light"] h4, [data-theme="light"] p, [data-theme="light"] span, [data-theme="light"] div {
            color: var(--text-main);
        }
        [data-theme="light"] .text-gray-200, [data-theme="light"] .text-gray-300, [data-theme="light"] .text-white { color: var(--text-main) !important; }
        [data-theme="light"] .text-gray-400, [data-theme="light"] .text-gray-500 { color: var(--text-muted) !important; }
        [data-theme="light"] .text-indigo-200 { color: #4338ca !important; }
        [data-theme="light"] .border-white\/5, [data-theme="light"] .border-white\/10, [data-theme="light"] .border-white\/20, [data-theme="light"] .border-white\/30 { border-color: var(--border-card) !important; }
        [data-theme="light"] .bg-white\/5 { background-color: rgba(0,0,0,0.03) !important; }
        [data-theme="light"] .bg-white\/10, [data-theme="light"] .bg-white\/20 { background-color: rgba(0,0,0,0.06) !important; }

        /* Fix Form Inputs in Dark Mode (Default) */
        input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]), select, textarea { 
            background-color: #1e293b !important; 
            border-color: #334155 !important; 
            color: #f8fafc !important;
        }

        /* Fix Form Inputs in Light Mode */
        [data-theme="light"] input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]), [data-theme="light"] select, [data-theme="light"] textarea { 
            background-color: #ffffff !important; 
            border-color: #cbd5e1 !important; 
            color: #1e293b !important;
        }

        /* Protect elements that MUST stay white even in light mode */
        [data-theme="light"] button *, [data-theme="light"] .badge, [data-theme="light"] a.bg-indigo-600, [data-theme="light"] a.bg-gradient-to-r, [data-theme="light"] .text-white-always { color: #ffffff !important; }
        [data-theme="light"] .bg-gradient-to-br .text-white, [data-theme="light"] .bg-gradient-to-r .text-white { color: #ffffff !important; }
        /* Fix SVG icon colors */
        [data-theme="light"] nav svg.text-white { color: var(--text-main) !important; }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</head>
<body class="font-sans antialiased" style="min-height: 100vh;">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 glass-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-white">Perpustakaan</span>
                </div>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('siswa.dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('siswa.dashboard') ? 'bg-indigo-500/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}">Dashboard</a>
                    <a href="{{ route('siswa.peminjaman') }}" class="px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('siswa.peminjaman') ? 'bg-indigo-500/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}">Peminjaman</a>
                    <a href="{{ route('siswa.pengembalian') }}" class="px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('siswa.pengembalian') ? 'bg-indigo-500/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}">Pengembalian</a>
                    <a href="{{ route('siswa.riwayat') }}" class="px-3 py-2 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('siswa.riwayat') ? 'bg-indigo-500/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}">Riwayat</a>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2">
                    <button id="themeToggle" class="w-8 h-8 rounded-full flex items-center justify-center bg-white/5 hover:bg-white/10 transition-colors border border-white/10 text-indigo-400" title="Toggle Theme">
                        <svg id="themeIconDark" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg id="themeIconGradient" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm font-bold shadow-md shadow-indigo-500/20">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-200 hidden lg:inline-block">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors p-2" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if(session('success'))
            <div class="px-4 py-3 rounded-xl mb-4 flex items-center shadow-sm" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #6ee7b7;" id="flash-success">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="px-4 py-3 rounded-xl mb-4 flex items-center shadow-sm" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5;" id="flash-error">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="px-4 py-3 rounded-xl mb-4 flex items-center shadow-sm" style="background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2); color: #93c5fd;">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">{{ session('info') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="px-4 py-3 rounded-xl mb-4 flex items-center shadow-sm" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); color: #fcd34d;" id="flash-warning">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="text-sm font-medium">{{ session('warning') }}</span>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-6">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 z-50 w-full h-16 glass-header border-t" style="border-top-color: var(--border-card);">
        <div class="grid h-full max-w-lg grid-cols-4 mx-auto font-medium">
            <a href="{{ route('siswa.dashboard') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-white/5 transition-colors {{ request()->routeIs('siswa.dashboard') ? 'text-indigo-500' : 'text-gray-400 hover:text-gray-200' }}">
                <svg class="w-6 h-6 mb-1 {{ request()->routeIs('siswa.dashboard') ? 'text-indigo-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px]">Home</span>
            </a>
            <a href="{{ route('siswa.peminjaman') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-white/5 transition-colors {{ request()->routeIs('siswa.peminjaman') ? 'text-indigo-500' : 'text-gray-400 hover:text-gray-200' }}">
                <svg class="w-6 h-6 mb-1 {{ request()->routeIs('siswa.peminjaman') ? 'text-indigo-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span class="text-[10px]">Pinjam</span>
            </a>
            <a href="{{ route('siswa.pengembalian') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-white/5 transition-colors {{ request()->routeIs('siswa.pengembalian') ? 'text-indigo-500' : 'text-gray-400 hover:text-gray-200' }}">
                <svg class="w-6 h-6 mb-1 {{ request()->routeIs('siswa.pengembalian') ? 'text-indigo-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                <span class="text-[10px]">Kembali</span>
            </a>
            <a href="{{ route('siswa.riwayat') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-white/5 transition-colors {{ request()->routeIs('siswa.riwayat') ? 'text-indigo-500' : 'text-gray-400 hover:text-gray-200' }}">
                <svg class="w-6 h-6 mb-1 {{ request()->routeIs('siswa.riwayat') ? 'text-indigo-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-[10px]">Riwayat</span>
            </a>
        </div>
    </div>

    <script>
        setTimeout(() => {
            document.querySelectorAll('#flash-success, #flash-error, #flash-warning').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);

        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('themeToggle');
            if (themeToggleBtn) {
                const iconDark = document.getElementById('themeIconDark');
                const iconLight = document.getElementById('themeIconGradient');

                // Set initial icon state
                if (document.documentElement.getAttribute('data-theme') === 'light') {
                    iconLight.classList.add('hidden');
                    iconDark.classList.remove('hidden');
                } else {
                    iconDark.classList.add('hidden');
                    iconLight.classList.remove('hidden');
                }

                themeToggleBtn.addEventListener('click', function() {
                    let currentTheme = document.documentElement.getAttribute('data-theme');
                    let targetTheme = currentTheme === 'light' ? 'dark' : 'light';
                    
                    document.documentElement.setAttribute('data-theme', targetTheme);
                    localStorage.setItem('theme', targetTheme);

                    // Toggle Icons
                    if (targetTheme === 'light') {
                        iconLight.classList.add('hidden');
                        iconDark.classList.remove('hidden');
                    } else {
                        iconDark.classList.add('hidden');
                        iconLight.classList.remove('hidden');
                    }
                });
            }


            // SweetAlert Interceptor for forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if(form.action.includes('logout') || form.method.toLowerCase() === 'get') return;
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Aksi',
                        text: "Apakah Anda yakin ingin melanjutkan?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) { form.submit(); }
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
