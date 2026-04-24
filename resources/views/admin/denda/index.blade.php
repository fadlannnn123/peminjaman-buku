@extends('layouts.admin')
@section('title', 'Data Denda')
@section('subtitle', 'Monitoring denda keterlambatan pengembalian buku')

@section('content')
    <div class="rounded-2xl overflow-hidden" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <form method="GET" action="{{ route('admin.denda.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..."
                    class="px-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full md:w-64" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0;" placeholder-color="#6b7280">
                
                <select name="status" class="px-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0;">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="dibayar" {{ request('status') === 'dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                </select>

                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-xl transition-colors" style="background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.2);">
                    Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.denda.index') }}" class="px-4 py-2 text-sm font-medium rounded-xl transition-colors" style="background: rgba(255,255,255,0.05); color: #9ca3af;">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Dikembalikan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Denda</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                    @forelse($dendas as $i => $denda)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $dendas->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-200">
                                {{ $denda->pengembalian->peminjaman->user->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ $denda->pengembalian->tanggal_dikembalikan->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-red-400">
                                Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($denda->status_bayar === 'belum_bayar')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(245,158,11,0.15); color: #fbbf24;">
                                        Belum Dibayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: rgba(16,185,129,0.15); color: #6ee7b7;">
                                        Sudah Dibayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($denda->status_bayar === 'belum_bayar')
                                    <form method="POST" action="{{ route('admin.denda.bayar', $denda->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all" style="background: linear-gradient(135deg, #10b981, #059669); color: white;"
                                                onclick="return confirm('Konfirmasi denda telah dibayar?')">
                                            Bayar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-600 font-medium text-xs">✓ Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-600">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Tidak ada data denda ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($dendas->hasPages())
            <div class="p-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                {{ $dendas->links() }}
            </div>
        @endif
    </div>
@endsection
