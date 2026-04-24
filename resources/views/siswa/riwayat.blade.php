@extends('layouts.siswa')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Riwayat Peminjaman</h1>
        <p class="text-gray-400 mt-1">Histori peminjaman dan pengembalian buku Anda</p>
    </div>

    <div class="rounded-3xl shadow-sm overflow-hidden" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Buku Pinjaman</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Denda (Jika Ada)</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                    @forelse($riwayat as $r)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-5">
                                <div class="text-sm font-bold text-gray-200 block mb-1">Pinjam: {{ $r->tanggal_pinjam->format('d M Y') }}</div>
                                @if($r->tanggal_kembali)
                                    <div class="text-xs text-gray-500 font-medium">Batas: <span class="text-gray-400">{{ $r->tanggal_kembali->format('d M Y') }}</span></div>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <ul class="space-y-2">
                                    @foreach($r->detailPeminjaman as $d)
                                        <li class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded flex items-center justify-center font-bold text-[10px]" style="background: rgba(255,255,255,0.05); color: #9ca3af;">{{ $d->jumlah }}</span>
                                            <span class="text-sm text-gray-300 font-medium">{{ $d->buku->judul }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-5">
                                @if($r->status_pinjaman === 'menunggu')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" style="background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2);">Menunggu Pinjam</span>
                                @elseif($r->status_pinjaman === 'ditolak')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" style="background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.2);">Ditolak</span>
                                @elseif($r->status_pinjaman === 'dipinjam')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" style="background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.2);">Masih Dipinjam</span>
                                @elseif($r->status_pinjaman === 'menunggu_pengembalian')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" style="background: rgba(139,92,246,0.15); color: #a78bfa; border: 1px solid rgba(139,92,246,0.2);">Proses Kembali</span>
                                @elseif($r->status_pinjaman === 'terlambat')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" style="background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.2);">Terlambat</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" style="background: rgba(16,185,129,0.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.2);">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                @if($r->pengembalian && $r->pengembalian->denda && $r->pengembalian->denda->jumlah_denda > 0)
                                    <div class="text-sm font-bold text-red-400 mb-2">
                                        Rp {{ number_format($r->pengembalian->denda->jumlah_denda, 0, ',', '.') }}
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded border {{ $r->pengembalian->denda->status_bayar === 'dibayar' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20' }}">
                                        {{ str_replace('_', ' ', $r->pengembalian->denda->status_bayar) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500 font-medium italic">Tidak ada denda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                <div class="w-16 h-16 rounded-full mx-auto mb-3 flex items-center justify-center" style="background: rgba(255,255,255,0.05);">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                Belum ada riwayat transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayat->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>
@endsection
