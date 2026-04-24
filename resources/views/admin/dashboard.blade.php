@extends('layouts.admin')
@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di panel admin perpustakaan')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="rounded-2xl p-5 transition-all hover:-translate-y-0.5 card-bg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Buku</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalBooks }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl p-5 transition-all hover:-translate-y-0.5 card-bg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Anggota</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalMembers }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl p-5 transition-all hover:-translate-y-0.5 card-bg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Sedang Dipinjam</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $activeBorrows }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="rounded-2xl p-5 transition-all hover:-translate-y-0.5 card-bg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Denda</p>
                    <p class="text-2xl font-bold text-red-400 mt-1">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Notification Cards -->
    @if($pendingPeminjaman > 0 || $pendingPengembalian > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
            @if($pendingPeminjaman > 0)
                <a href="{{ route('admin.konfirmasi.index') }}" class="rounded-2xl p-5 flex items-center gap-4 transition-all hover:-translate-y-0.5 group" style="background: linear-gradient(135deg, rgba(245,158,11,0.1), rgba(217,119,6,0.05)); border: 1px solid rgba(245,158,11,0.2);">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-xl font-black text-white">{{ $pendingPeminjaman }}</span>
                    </div>
                    <div>
                        <p class="font-bold text-amber-300 group-hover:text-amber-200 transition-colors">Peminjaman Menunggu Persetujuan</p>
                        <p class="text-xs text-amber-400/60">Klik untuk review →</p>
                    </div>
                </a>
            @endif
            @if($pendingPengembalian > 0)
                <a href="{{ route('admin.konfirmasi.index') }}" class="rounded-2xl p-5 flex items-center gap-4 transition-all hover:-translate-y-0.5 group" style="background: linear-gradient(135deg, rgba(139,92,246,0.1), rgba(124,58,237,0.05)); border: 1px solid rgba(139,92,246,0.2);">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-xl font-black text-white">{{ $pendingPengembalian }}</span>
                    </div>
                    <div>
                        <p class="font-bold text-purple-300 group-hover:text-purple-200 transition-colors">Pengembalian Menunggu Konfirmasi</p>
                        <p class="text-xs text-purple-400/60">Klik untuk review →</p>
                    </div>
                </a>
            @endif
        </div>
    @endif

    <!-- Chart Peminjaman -->
    <div class="rounded-2xl mb-8 p-6 card-bg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Statistik Transaksi (Tahun {{ now()->year }})</h3>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="peminjamanChart"></canvas>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="rounded-2xl overflow-hidden card-bg">
        <div class="px-6 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <h3 class="text-lg font-semibold text-white">Transaksi Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                    @forelse($recentTransactions as $t)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-300">{{ $t->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                @foreach($t->detailPeminjaman as $d)
                                    <span class="block">{{ $d->buku->judul }} ({{ $d->jumlah }})</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $t->tanggal_pinjam->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($t->status_pinjaman === 'menunggu')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(245,158,11,0.15); color: #fbbf24;">Menunggu</span>
                                @elseif($t->status_pinjaman === 'dipinjam')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(59,130,246,0.15); color: #60a5fa;">Dipinjam</span>
                                @elseif($t->status_pinjaman === 'ditolak')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(239,68,68,0.15); color: #f87171;">Ditolak</span>
                                @elseif($t->status_pinjaman === 'menunggu_pengembalian')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(139,92,246,0.15); color: #a78bfa;">Menunggu Kembali</span>
                                @elseif($t->status_pinjaman === 'terlambat')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(239,68,68,0.15); color: #f87171;">Terlambat</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(16,185,129,0.15); color: #6ee7b7;">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-600">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        const dataPeminjaman = @json($chartDataPeminjaman ?? array_fill(0, 12, 0));
        const dataPengembalian = @json($chartDataPengembalian ?? array_fill(0, 12, 0));
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Dipinjam',
                        data: dataPeminjaman,
                        backgroundColor: 'rgba(99, 102, 241, 0.7)',
                        borderColor: 'rgba(99, 102, 241, 0.9)',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.7,
                        categoryPercentage: 0.4
                    },
                    {
                        label: 'Dikembalikan',
                        data: dataPengembalian,
                        backgroundColor: 'rgba(139, 92, 246, 0.6)',
                        borderColor: 'rgba(139, 92, 246, 0.8)',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.7,
                        categoryPercentage: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { family: 'Inter', size: 13 },
                            color: '#94a3b8',
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 15, 26, 0.95)',
                        titleFont: { size: 13, family: 'Inter' },
                        titleColor: '#e2e8f0',
                        bodyFont: { size: 14, family: 'Inter', weight: 'bold' },
                        bodyColor: '#e2e8f0',
                        padding: 12,
                        cornerRadius: 8,
                        borderColor: 'rgba(99,102,241,0.2)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { family: 'Inter' },
                            color: '#4b5563'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.04)',
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 12 },
                            color: '#4b5563'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
