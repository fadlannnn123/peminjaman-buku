@extends('layouts.siswa')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Selamat Datang, {{ $user->name }}!</h1>
        <p class="text-gray-400 mt-1">Dashboard Siswa Perpustakaan</p>
    </div>

    @if(!$member)
        <div class="rounded-2xl p-6 mb-8 flex items-start gap-4 shadow-sm" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2);">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-amber-400">Anda Belum Terdaftar Sebagai Anggota</h3>
                <p class="text-amber-200 mt-1 text-sm">Daftarkan diri Anda terlebih dahulu untuk dapat meminjam buku.</p>
                <a href="{{ route('siswa.daftar-anggota') }}"
                   class="inline-flex items-center mt-3 px-5 py-2.5 text-white text-sm font-bold rounded-xl transition-all shadow-md gap-2" style="background: linear-gradient(135deg, #f59e0b, #d97706);" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Daftar Anggota Sekarang
                </a>
            </div>
        </div>
    @else
        <!-- Member Card -->
        <div class="rounded-2xl p-6 mb-8 text-white shadow-xl shadow-indigo-500/20 flex flex-col md:flex-row justify-between md:items-center gap-4 relative overflow-hidden" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border: 1px solid rgba(255,255,255,0.1);">
            <!-- Decorative Elements -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-indigo-900/40 rounded-full blur-xl"></div>
            
            <div class="relative z-10">
                <p class="text-indigo-200 text-sm font-bold uppercase tracking-wider">Kartu Anggota</p>
                <h3 class="text-2xl font-bold mt-1 text-white">{{ $user->name }}</h3>
                <p class="text-indigo-200 text-sm mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $member->alamat }} • 
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $member->no_telepon }}
                </p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shrink-0 border border-white/30 backdrop-blur-md relative z-10 shadow-inner">
                <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
            <div class="rounded-2xl p-5 border transition-all hover:-translate-y-0.5 card-bg">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <svg class="w-6 h-6 text-white-always" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div><p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Buku Sedang Dipinjam</p><p class="text-2xl font-bold mt-0.5">{{ collect($activeBorrows)->sum(fn($b) => $b->detailPeminjaman->count()) }}</p></div>
                </div>
            </div>
            <div class="rounded-2xl p-5 border transition-all hover:-translate-y-0.5 card-bg">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <svg class="w-6 h-6 text-white-always" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div><p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Buku Tersedia</p><p class="text-2xl font-bold mt-0.5">{{ $books->total() }}</p></div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="rounded-2xl mb-8 p-6 card-bg shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Statistik Riwayat Peminjaman (Tahun {{ $currentYear }})</h3>
            </div>
            <div class="relative h-[300px] w-full">
                <canvas id="borrowChart"></canvas>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($member)
            const isLightMode = document.documentElement.getAttribute('data-theme') === 'light';
            const textColor = isLightMode ? '#64748b' : '#9ca3af';
            const gridColor = isLightMode ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';

            const ctx = document.getElementById('borrowChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
            gradient.addColorStop(1, 'rgba(139, 92, 246, 0.8)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: @json($chartData),
                        backgroundColor: gradient,
                        borderRadius: 6,
                        borderSkipped: false,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isLightMode ? 'rgba(255,255,255,0.95)' : 'rgba(15, 23, 42, 0.95)',
                            titleColor: isLightMode ? '#1e293b' : '#fff',
                            bodyColor: isLightMode ? '#475569' : '#cbd5e1',
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Transaksi Peminjaman';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: textColor,
                                font: { size: 11 }
                            },
                            grid: {
                                color: gridColor,
                                drawBorder: false,
                            }
                        },
                        x: {
                            ticks: {
                                color: textColor,
                                font: { size: 11 }
                            },
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        @endif
    });
</script>
@endpush
