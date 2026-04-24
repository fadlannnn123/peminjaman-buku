@extends('layouts.admin')
@section('title', 'Konfirmasi')
@section('subtitle', 'Kelola permintaan peminjaman dan pengembalian buku')

@section('content')
    <!-- Pending Peminjaman Section -->
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">Peminjaman Menunggu Persetujuan</h2>
                <p class="text-sm text-gray-500">{{ $pendingPeminjaman->count() }} permintaan</p>
            </div>
        </div>

        @if($pendingPeminjaman->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pendingPeminjaman as $p)
                    <div class="rounded-2xl p-5 border transition-all hover:border-indigo-500/30" style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.06);">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    {{ strtoupper(substr($p->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">{{ $p->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $p->tanggal_pinjam->format('d M Y') }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" style="background: rgba(245,158,11,0.15); color: #fbbf24;">
                                Menunggu
                            </span>
                        </div>

                        <div class="mb-4 pl-3 border-l-2 border-indigo-500/30">
                            @foreach($p->detailPeminjaman as $d)
                                <p class="text-sm text-gray-300 py-1">
                                    <span class="font-medium text-white">{{ $d->buku->judul }}</span>
                                    <span class="text-gray-500 text-xs ml-1">({{ $d->jumlah }} buku)</span>
                                </p>
                            @endforeach
                        </div>

                        @if($p->tanggal_kembali)
                            <p class="text-xs text-gray-500 mb-4">
                                Rencana kembali: <span class="text-gray-300 font-medium">{{ $p->tanggal_kembali->format('d M Y') }}</span>
                            </p>
                        @endif

                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.konfirmasi.approve', $p->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #10b981, #059669); color: white;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                                        onclick="return confirm('Setujui peminjaman ini?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.konfirmasi.reject', $p->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2);" onmouseover="this.style.background='rgba(239,68,68,0.25)'" onmouseout="this.style.background='rgba(239,68,68,0.15)'"
                                        onclick="return confirm('Tolak peminjaman ini?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 rounded-2xl" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: rgba(255,255,255,0.05);">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-gray-500 font-medium">Tidak ada peminjaman yang menunggu persetujuan</p>
            </div>
        @endif
    </div>

    <!-- Pending Pengembalian Section -->
    <div>
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">Pengembalian Menunggu Konfirmasi</h2>
                <p class="text-sm text-gray-500">{{ $pendingPengembalian->count() }} permintaan</p>
            </div>
        </div>

        @if($pendingPengembalian->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pendingPengembalian as $p)
                    <div class="rounded-2xl p-5 border transition-all hover:border-purple-500/30" style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.06);">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    {{ strtoupper(substr($p->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">{{ $p->user->name }}</p>
                                    <p class="text-xs text-gray-500">Pinjam: {{ $p->tanggal_pinjam->format('d M Y') }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" style="background: rgba(139,92,246,0.15); color: #a78bfa;">
                                Menunggu
                            </span>
                        </div>

                        <div class="mb-4 pl-3 border-l-2 border-purple-500/30">
                            @foreach($p->detailPeminjaman as $d)
                                <p class="text-sm text-gray-300 py-1">
                                    <span class="font-medium text-white">{{ $d->buku->judul }}</span>
                                    <span class="text-gray-500 text-xs ml-1">({{ $d->jumlah }} buku)</span>
                                </p>
                            @endforeach
                        </div>

                        @php
                            $dendaTerlambat = $p->lateDays() * 5000;
                            $dendaKondisi = 0;
                            $kondisi = $p->pengembalian ? $p->pengembalian->kondisi_buku : 'baik';
                            if ($kondisi === 'hilang') $dendaKondisi = 50000;
                            elseif ($kondisi === 'rusak') $dendaKondisi = 20000;
                            $totalEstimasiDenda = $dendaTerlambat + $dendaKondisi;
                        @endphp

                        @if($totalEstimasiDenda > 0)
                            <div class="mb-4 px-3 py-2 rounded-lg flex flex-col gap-1.5" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.15);">
                                <span class="text-[10px] font-bold text-red-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Estimasi Denda
                                </span>
                                @if($dendaTerlambat > 0)
                                    <div class="flex items-center justify-between text-xs text-red-300">
                                        <span>Terlambat {{ $p->lateDays() }} hari</span>
                                        <span>Rp {{ number_format($dendaTerlambat, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if($dendaKondisi > 0)
                                    <div class="flex items-center justify-between text-xs text-red-300">
                                        <span>Buku {{ ucfirst($kondisi) }}</span>
                                        <span>Rp {{ number_format($dendaKondisi, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between text-xs font-bold text-red-400 pt-1 border-t border-red-500/20 mt-1">
                                    <span>Total Denda Dikenakan</span>
                                    <span>Rp {{ number_format($totalEstimasiDenda, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif

                        @if($p->pengembalian)
                            <div class="mb-4 px-3 py-2 rounded-lg flex items-center justify-between" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                <span class="text-xs text-gray-400 font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Kondisi Dilaporkan
                                </span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md
                                    {{ $p->pengembalian->kondisi_buku === 'baik' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 
                                      ($p->pengembalian->kondisi_buku === 'rusak' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20') }}">
                                    {{ $p->pengembalian->kondisi_buku }}
                                </span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.konfirmasi.approve-return', $p->id) }}">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                                    onclick="return confirm('Konfirmasi pengembalian buku ini?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Konfirmasi Pengembalian
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 rounded-2xl" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: rgba(255,255,255,0.05);">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-gray-500 font-medium">Tidak ada pengembalian yang menunggu konfirmasi</p>
            </div>
        @endif
    </div>
@endsection
