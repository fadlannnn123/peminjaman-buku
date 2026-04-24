<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku - Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link {
            position: relative;
            transition: all 0.2s ease;
        }
        .sidebar-link:hover { transform: translateX(4px); }
        .sidebar-link.active { transform: translateX(4px); }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: -16px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: linear-gradient(to bottom, #818cf8, #a78bfa);
            border-radius: 0 4px 4px 0;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.4s ease-out; }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        :root {
            --bg-body: #0f172a;
            --bg-sidebar: #1e293b;
            --bg-card: rgba(30,41,59,0.7);
            --border-card: rgba(255,255,255,0.1);
            --bg-glass: rgba(15,23,42,0.9);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }
        [data-theme="light"] {
            --bg-body: #f1f5f9;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --border-card: #cbd5e1;
            --bg-glass: rgba(241,245,249,0.9);
            --text-main: #1e293b;
            --text-muted: #64748b;
        }
        body { background: var(--bg-body); color: var(--text-main); transition: background-color 0.3s ease, color 0.3s ease; }
        .sidebar-bg { background: var(--bg-sidebar); border-right: 1px solid var(--border-card); transition: background-color 0.3s ease, border-color 0.3s ease; }
        .card-bg { background: var(--bg-card); border: 1px solid var(--border-card); transition: background-color 0.3s ease, border-color 0.3s ease; }
        .glass-header { background: var(--bg-glass); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-card); transition: background-color 0.3s ease, border-color 0.3s ease; }

        /* SIDEBAR RESPONSIVE */
        #sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        #sidebar.open { transform: translateX(0); }
        @media (min-width: 1024px) {
            #sidebar { transform: translateX(0) !important; }
            #sidebar-overlay { display: none !important; }
        }
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9;
            backdrop-filter: blur(2px);
        }
        #sidebar-overlay.show { display: block; }

        [data-theme="light"] h1, [data-theme="light"] h2, [data-theme="light"] h3, [data-theme="light"] h4, [data-theme="light"] p, [data-theme="light"] span, [data-theme="light"] div, [data-theme="light"] td, [data-theme="light"] th { color: var(--text-main); }
        [data-theme="light"] .text-gray-200, [data-theme="light"] .text-gray-300, [data-theme="light"] .text-white { color: var(--text-main) !important; }
        [data-theme="light"] .text-gray-400, [data-theme="light"] .text-gray-500 { color: var(--text-muted) !important; }
        [data-theme="light"] .text-indigo-200 { color: #4338ca !important; }
        [data-theme="light"] .border-white\/5, [data-theme="light"] .border-white\/10, [data-theme="light"] .border-white\/20, [data-theme="light"] .border-white\/30 { border-color: var(--border-card) !important; }
        [data-theme="light"] .bg-white\/5 { background-color: rgba(0,0,0,0.03) !important; }
        [data-theme="light"] .bg-white\/10, [data-theme="light"] .bg-white\/20 { background-color: rgba(0,0,0,0.06) !important; }
        input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]), select, textarea { background-color: #1e293b !important; border-color: #334155 !important; color: #f8fafc !important; }
        [data-theme="light"] input:not([type="submit"]):not([type="checkbox"]):not([type="radio"]), [data-theme="light"] select, [data-theme="light"] textarea { background-color: #ffffff !important; border-color: #cbd5e1 !important; color: #1e293b !important; }
        [data-theme="light"] button *, [data-theme="light"] .badge, [data-theme="light"] a.bg-indigo-600, [data-theme="light"] a.bg-gradient-to-r, [data-theme="light"] .text-white-always { color: #ffffff !important; }
        [data-theme="light"] .bg-gradient-to-br .text-white, [data-theme="light"] .bg-gradient-to-r .text-white { color: #ffffff !important; }
        [data-theme="light"] aside svg.text-white, [data-theme="light"] nav svg.text-white { color: var(--text-main) !important; }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</head>
<body class="font-sans antialiased" style="min-height: 100vh;">
    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebar-overlay"></div>

    <div class="flex min-h-screen relative">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 flex flex-col fixed h-full z-10 shadow-2xl sidebar-bg">
            <!-- Decorative glow -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/3 -left-12 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-1/4 -right-8 w-40 h-40 bg-indigo-400/5 rounded-full blur-3xl"></div>
            </div>

            <div class="px-6 py-6 border-b border-white/5 relative">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-white leading-tight">Perpustakaan</h2>
                        <p class="text-indigo-400 text-xs font-bold tracking-widest">ADMIN PANEL</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto sidebar-scroll relative">
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'active bg-indigo-500/20 text-indigo-300 shadow-lg shadow-indigo-500/10' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <!-- Konfirmasi Menu (with notification badge) -->
                @php
                    $totalPending = \App\Models\Peminjaman::pending()->count() + \App\Models\Peminjaman::pendingReturn()->count();
                @endphp
                <a href="{{ route('admin.konfirmasi.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.konfirmasi.*') ? 'active bg-indigo-500/20 text-indigo-300 shadow-lg shadow-indigo-500/10' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Konfirmasi
                    @if($totalPending > 0)
                        <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold bg-red-500 text-white rounded-full pulse-dot">{{ $totalPending }}</span>
                    @endif
                </a>

                <!-- Buku Menu -->
                <div class="pt-4 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-2">Manajemen Buku</p>
                    <a href="{{ route('admin.books.index') }}"
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.books.index') ? 'active bg-white/10 text-gray-200' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Daftar Buku
                    </a>
                    <a href="{{ route('admin.books.create') }}"
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.books.create') ? 'active bg-white/10 text-gray-200' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Buku
                    </a>
                </div>

                <!-- Kategori Menu -->
                <div class="pt-2 pb-1">
                    <a href="{{ route('admin.kategori.index') }}"
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.kategori.index') ? 'active bg-white/10 text-gray-200' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Daftar Kategori
                    </a>
                    <a href="{{ route('admin.kategori.create') }}"
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.kategori.create') ? 'active bg-white/10 text-gray-200' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Kategori
                    </a>
                </div>

                <!-- Anggota Menu -->
                <div class="pt-4 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-2">Pengguna</p>
                    <a href="{{ route('admin.members.index') }}"
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.members.index') ? 'active bg-white/10 text-gray-200' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Daftar Anggota
                    </a>
                    <a href="{{ route('admin.members.create') }}"
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.members.create') ? 'active bg-white/10 text-gray-200' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Anggota
                    </a>
                </div>

                <!-- Transaksi & Denda Menu -->
                <div class="pt-4 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-2">Operasional</p>
                    <a href="{{ route('admin.transactions.index') }}"
                       class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.transactions.*') ? 'active bg-white/10 text-gray-200' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Daftar Transaksi
                    </a>
                    @php
                        $unpaidDenda = \App\Models\Denda::where('status_bayar', 'belum_bayar')->count();
                    @endphp
                    <a href="{{ route('admin.denda.index') }}"
                       class="sidebar-link flex items-center gap-3 mt-1 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.denda.*') ? 'active bg-white/10 text-gray-200' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Denda
                        @if($unpaidDenda > 0)
                            <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold bg-amber-500 text-white rounded-full pulse-dot">{{ $unpaidDenda }}</span>
                        @endif
                    </a>
                </div>
            </nav>

            <!-- User Info -->
            <div class="px-4 py-4 border-t border-white/5 m-4 rounded-2xl relative card-bg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-black text-xs shadow-md shadow-indigo-500/30">
                        AD
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-white/5 hover:bg-red-500/20 text-gray-500 hover:text-red-400 text-xs font-bold tracking-wider rounded-xl transition-all flex justify-center items-center gap-1.5 border border-white/5 hover:border-red-500/30">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        LOGOUT
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 lg:ml-64">
            <div class="px-4 sm:px-6 py-4 flex justify-between items-center sticky top-0 z-20 glass-header">
                <div class="flex items-center gap-3">
                    <!-- Hamburger Button (mobile only) -->
                    <button id="sidebarToggle" class="lg:hidden w-9 h-9 rounded-xl flex items-center justify-center bg-white/5 hover:bg-white/10 transition-colors border border-white/10 text-gray-400" title="Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h1 class="text-base sm:text-xl font-bold text-white">@yield('title', 'Dashboard')</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 hidden sm:block">@yield('subtitle')</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <button id="themeToggle" class="w-9 h-9 rounded-full flex items-center justify-center bg-white/5 hover:bg-white/10 transition-colors border border-white/10 text-indigo-400" title="Toggle Theme">
                        <svg id="themeIconDark" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg id="themeIconGradient" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium hidden sm:block">{{ now()->format('d M Y') }}</p>
                    <div class="w-2 h-2 bg-emerald-400 rounded-full pulse-dot"></div>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-8 animate-fade-in">
                @if(session('success'))
                    <div class="mb-6 px-4 py-3 rounded-xl text-sm flex items-center gap-2" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #6ee7b7;">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 px-4 py-3 rounded-xl text-sm flex items-center gap-2" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5;">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="mb-6 px-4 py-3 rounded-xl text-sm flex items-center gap-2" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); color: #fcd34d;">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        {{ session('warning') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === SIDEBAR TOGGLE ===
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const sidebarToggle = document.getElementById('sidebarToggle');

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            // Close sidebar on nav link click (mobile)
            sidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) closeSidebar();
                });
            });

            // === THEME TOGGLE ===
            const themeToggleBtn = document.getElementById('themeToggle');
            const iconDark = document.getElementById('themeIconDark');
            const iconLight = document.getElementById('themeIconGradient');

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
                if (targetTheme === 'light') {
                    iconLight.classList.add('hidden');
                    iconDark.classList.remove('hidden');
                } else {
                    iconDark.classList.add('hidden');
                    iconLight.classList.remove('hidden');
                }
            });

            // === SWEETALERT INTERCEPTOR ===
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if(form.action.includes('logout') || form.method.toLowerCase() === 'get') return;
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Aksi ini akan diproses oleh sistem.",
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
</body>
</html>
